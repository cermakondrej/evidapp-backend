<?php

declare(strict_types=1);

namespace App\Service\Export;

use App\DTO\ExportRow;
use App\Entity\EmployeeWorkExport;
use App\DTO\ExportOutput;
use App\Factory\DTO\ExportOutputFactory;
use App\Factory\DTO\ExportRowFactory;
use App\Factory\ValueObject\DayInMonthFactory;
use App\Factory\ValueObject\MonthFactory;
use App\ValueObject\DayInMonth;
use App\ValueObject\Month;

class EmployeeWorkExporter
{
    public const WORKDAY_HOURS = 8;

    /** @var AbsenceHandler */
    private $absenceHandler;

    /** @var DayInMonthFactory */
    private $dayInMonthFactory;

    /** @var MonthFactory */
    private $monthFactory;

    /** @var ExportRowFactory */
    private $exportRowFactory;

    /** @var ExportOutputFactory */
    private $exportOutputFactory;

    public function __construct(
        AbsenceHandler $absenceHandler,
        DayInMonthFactory $dayInMonthFactory,
        MonthFactory $monthFactory,
        ExportRowFactory $exportRowFactory,
        ExportOutputFactory $exportOutputFactory
    )
    {
        $this->absenceHandler = $absenceHandler;
        $this->dayInMonthFactory = $dayInMonthFactory;
        $this->monthFactory = $monthFactory;
        $this->exportRowFactory = $exportRowFactory;
        $this->exportOutputFactory = $exportOutputFactory;
    }

    public function createExport(EmployeeWorkExport $export): ExportOutput
    {
        $output = $this->exportOutputFactory->create($export);

        $exportRows = [];
        $hoursWorked = 0;
        $totalHours = 0;
        $month = $this->monthFactory->create($export->getMonth(), $export->getYear());

        $totalHours += $this->absenceHandler->handleAbsences($exportRows, $output, $export);

        // TODO extract this into function plz
        $workHours = $month->getNumberOfWorkingDays() * self::WORKDAY_HOURS * $export->getWork()->getWorkload();

        $output->setWorkHours(number_format($workHours, 2));
        $hoursWorked += $this->fillWeekendsAndHolidays($exportRows, $month, $export->getWork()->getWorkload());
        $totalHours += $hoursWorked;

        $hoursWorked += $this->fillRest($exportRows, $month, $workHours - $totalHours, $export);

        $output->setTotalWorked(number_format($hoursWorked, 2));
        // order exportRows by key
        ksort($exportRows);
        $output->setExportRows($exportRows);
        return $output;
    }

    private function fillWeekendsAndHolidays(array &$exportRows, Month $month, float $workload): float
    {
        $worked = 0;
        /** @var DayInMonth $day */
        foreach ($month->getDaysInMonth() as $key => $day) {

            $row = $this->exportRowFactory->createEmpty($key + 1);

            if ($day->isWeekend()) {
                $row->setNote('Víkend');
                $exportRows[$key + 1] = $row;

            } else if ($day->isHoliday()) {

                $row->setNote('Státní Svátek');
                $row->setHoursWorked(number_format(self::WORKDAY_HOURS * $workload, 2));
                $worked += self::WORKDAY_HOURS * $workload;

                $exportRows[$key + 1] = $row;
            }
        }

        return $worked;
    }

    private function fillRest(array &$exportRows, Month $month, float $remainingHours, EmployeeWorkExport $export): float
    {
        $workToDo = $remainingHours;
        $weeks = $this->getWeeksInMonth($exportRows, $month);

        $hoursPerWeek = $this->getHoursPerFullWeek($export->getWork()->getWorkload());

        $fullWeekHour = floor($hoursPerWeek / 5);

        // FILL THE FULL WEEKS, TODO REFACTOR
        foreach ($weeks['full'] as $week) {
            $extraHours = fmod($hoursPerWeek, 5);
            $overHours = $extraHours > 1 ? 1 : $extraHours;
            $worked = $fullWeekHour + $overHours;;
            foreach ($week as $key => $day) {
                $remainingHours -= $worked;
                $extraHours -= $overHours;

                $row = $this->exportRowFactory->createEmployee((int)$key, $worked, $export->getWorkStart(), $export->getBreakStart());
                $overHours = $extraHours > 1 ? 1 : $extraHours;
                $worked = $fullWeekHour + $overHours;

                $exportRows[$key] = $row;
            }
        }

        $partialDayHour = (int) ($remainingHours/$weeks['number_of_partial_days']);
        $extraHours = fmod($remainingHours, $weeks['number_of_partial_days']);

        $partialWeeksNum = count($weeks['partial']);
        
        // FILL THE PARTIAL WEEKS, TODO REFACTOR
        foreach ($weeks['partial'] as $week) {

            $weekExtraHours = $extraHours < 1 ? $extraHours : $extraHours/$partialWeeksNum;
            $weekExtraHours = $extraHours >= 1 && $weekExtraHours < 1 ? 1 : $weekExtraHours;
            $overHours = $weekExtraHours > 1 ? 1 : $weekExtraHours;
            $worked = $partialDayHour + $overHours;;
            --$partialWeeksNum;
            foreach ($week as $key => $day) {

                $remainingHours -= $worked;
                $extraHours -= $overHours;
                $weekExtraHours -= $overHours;

                $row = $this->exportRowFactory->createEmployee((int)$key, $worked, $export->getWorkStart(), $export->getBreakStart());

                $overHours = $weekExtraHours > 1 ? 1 : $weekExtraHours;
                $worked = $partialDayHour + $overHours;;
                $exportRows[$key] = $row;
            }
        }

       if($remainingHours != 0)
       {
           throw new \Exception("Remaining hours has to be 0. Actual: ". $remainingHours);
       }

        return $workToDo;

    }

    private function getHoursPerFullWeek(float $workload): float
    {
        return 40 * $workload;
    }


    private function getWeeksInMonth(array &$exportRows, Month $month): array
    {
        $cnt = 0;
        $fullWeeks = [];
        $partialWeeks = [];
        $week = [];
        $numberOfPartialDays = 0;

        foreach ($month->getDaysInMonth() as $key => $day) {

            if (!$day->isHoliday() && !$day->isWeekend() && !array_key_exists($key + 1, $exportRows)) {
                $week[$key + 1] = $day;
                ++$cnt;
                continue;
            }

            if ($week) {

                if ($cnt !== 5) {
                    $partialWeeks[] = $week;
                    $numberOfPartialDays += $cnt;
                    $cnt = 0;
                    $week = [];
                    continue;
                }

                $fullWeeks[] = $week;
                $cnt = 0;
                $week = [];
                continue;
            }
        }

        if ($cnt > 0 && $cnt < 5) {
            $numberOfPartialDays += $cnt;
            $partialWeeks[] = $week;
        }

        if ($cnt == 5) {
            $fullWeeks[] = $week;
        }
        // TODO VALUE OBJECT FOR THIS SHIT PLS
        return array(
            'partial' => $partialWeeks,
            'full' => $fullWeeks,
            'number_of_blocks' => count($partialWeeks) + count($fullWeeks),
            'number_of_partial_days' => $numberOfPartialDays,
        );
    }
}

