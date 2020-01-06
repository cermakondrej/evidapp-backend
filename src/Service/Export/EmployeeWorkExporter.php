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
    ) {
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

        $output->setWorkHours($workHours);
        $hoursWorked += $this->fillWeekendsAndHolidays($exportRows, $month, $export->getWork()->getWorkload());
        $totalHours += $hoursWorked;

        $hoursWorked += $this->fillRest($exportRows, $month, $workHours - $totalHours);

        $output->setTotalWorked($hoursWorked);
        $output->setTotalHours($totalHours);
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
            if (!array_key_exists($key + 1, $exportRows)) {
                $row = $this->exportRowFactory->createEmpty($key + 1);
                $worked += $this->fillHolidayOrWeekend($row, $day, $workload);
                $exportRows[$key + 1] = $row;
            }
        }

        return $worked;
    }

    private function fillRest(array &$exportRows, Month $month, float $remainingHours): float
    {
        dump($month);
        dump($remainingHours);die;
        $worked = 0;
        /** @var DayInMonth $day */
        foreach ($month->getDaysInMonth() as $key => $day) {
            if (!array_key_exists($key + 1, $exportRows)) {
                $row = $this->exportRowFactory->createEmpty($key + 1);
                $this->checkHolidayOrWeekend($row, $day);
                $exportRows[$key + 1] = $row;
            }
        }

        return $worked;
    }

    private function fillHolidayOrWeekend(ExportRow &$row, DayInMonth $dayInMonth, float $workload): float
    {
        if ($dayInMonth->isWeekend()) {
            $row->setNote('Víkend');
            return 0;
        }

        if ($dayInMonth->isHoliday()) {
            $row->setNote('Státní Svátek');
            $row->setHoursWorked(self::WORKDAY_HOURS * $workload);
            return self::WORKDAY_HOURS * $workload;
        }

        $row->setDarkRow(false);
        return 0;
    }

}
