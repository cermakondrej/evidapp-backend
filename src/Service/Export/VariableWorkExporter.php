<?php

declare(strict_types=1);

namespace App\Service\Export;

use App\Entity\Holiday;
use App\Entity\VariableWorkExport;
use App\Entity\WorkExport;
use App\Factory\ValueObject\AbsenceFactory;
use App\Factory\ValueObject\ShiftFactory;
use App\ValueObject\DayInMonth;
use App\ValueObject\Absence;
use App\ValueObject\ExportOutput;
use App\ValueObject\ExportRow;
use App\ValueObject\Month;
use App\ValueObject\Shift;
use DateTimeImmutable;
use DateTime;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;

class VariableWorkExporter
{
    /** @var Holiday[] */
    private $holidays;

    /** @var ShiftFactory $shiftFactory */
    private $shiftFactory;

    /** @var AbsenceFactory $absenceFactory */
    private $absenceFactory;

    public function __construct(EntityManagerInterface $em, ShiftFactory $shiftFactory, AbsenceFactory $absenceFactory)
    {
        $holidays = $em->getRepository(Holiday::class)->findAll();
        $this->holidays = $holidays;
        $this->shiftFactory = $shiftFactory;
        $this->absenceFactory = $absenceFactory;
    }


    public function createExport(VariableWorkExport $export): ExportOutput
    {
        $output = $this->generateHeaderFromExport($export);

        $exportRows = [];
        $hoursWorked = 0;
        $totalHours = 0;

        /** @var Shift $shift */
        foreach ($export->getShifts() as $shift) {
            $shift = $this->shiftFactory->create($shift);
            $dayInMonth = $this->getDayInMonth($shift->getDay(), $export->getMonth(), $export->getYear());
            $outputRow = $this->createRowFromShift($shift);

            $this->computeHoursWorked($outputRow, $hoursWorked, $shift);
            $this->checkHolidayOrWeekend($outputRow, $dayInMonth);

            $exportRows[$shift->getDay()] = $outputRow;
        }

        $month = $this->getDaysWithHolidays($export->getMonth(), $export->getYear());
        $totalHours += $this->handleAbsences($exportRows, $output, $export);

        //TODO fill in rest of export rows with weekdays, holidays or blank lines
        $this->fillRest($exportRows, $month);

        $output->setWorkHours($month->getNumberOfWorkingDays() * $export->getWork()->getWorkload());

        $output->setTotalWorked($hoursWorked);
        $output->setTotalHours($totalHours);
        // order exportRows by key
        ksort($exportRows);
        $output->setExportRows($exportRows);

        return $output;
    }

    private function createRowFromShift(Shift $shift): ExportRow
    {
        $outputRow = new ExportRow($shift->getDay());

        $outputRow->setWorkStart($shift->getWorkStart());
        $outputRow->setWorkEnd($shift->getWorkEnd());
        $outputRow->setBreakStart($shift->getBreakStart());
        $outputRow->setBreakEnd($shift->getBreakEnd());

        return $outputRow;
    }

    private function generateHeaderFromExport(WorkExport $export): ExportOutput
    {
        $output = new ExportOutput();

        $output->setFullName($export->getEmployee()->getFullName());
        $output->setJobName($export->getJobName());
        $output->setWorkload($export->getWork()->getWorkload());
        $output->setCompanyName($export->getWork()->getCompany()->getName());
        $output->setMonth($export->getMonth());
        $output->setYear($export->getYear());

        return $output;
    }

    private function fillRest(array &$exportRows, Month $month)
    {
        /** @var DayInMonth $day */
        foreach($month->getDaysInMonth() as $key=>$day){
            if(array_key_exists($key, $exportRows))
                continue;

            $row = new ExportRow($key);
            $this->checkHolidayOrWeekend($row, $day);
            $exportRows[$key] = $row;
        }
    }
    private function handleAbsences(array &$output, ExportOutput &$exportOutput, WorkExport $export): float
    {
        $totalBillable = $this->handleBillableFreeTime($output, $export->getBillableFreeTime(), $exportOutput);
        $totalVacation = $this->handleVacation($output, $export->getVacation(), $exportOutput);
        $totalUnpaidVacation = $this->handleUnpaidVacation($output, $export->getUnpaidVacation(), $exportOutput);
        $totalNursing = $this->handleNursing($output, $export->getNursing(), $exportOutput);
        $totalSickness = $this->handleSickness($output, $export->getSickness(), $exportOutput);

        $exportOutput->setTotalBillableFreeTime($totalBillable);
        $exportOutput->setTotalVacation($totalVacation);
        $exportOutput->setTotalUnpaidVacation($totalUnpaidVacation);
        $exportOutput->setTotalNursing($totalNursing);
        $exportOutput->setTotalSickness($totalSickness);

        return $totalNursing + $totalUnpaidVacation + $totalVacation + $totalBillable + $totalSickness;

    }

    private function handleBillableFreeTime(array &$output, array $billableFreeTime, ExportOutput $exportOutput): float
    {
        return $this->calculateAbsences($output, $billableFreeTime, 'Volno s náhradou mzdy', $exportOutput);
    }

    private function handleVacation(array &$output, array $vacation, ExportOutput $exportOutput): float
    {
        return $this->calculateAbsences($output, $vacation, 'Dovolená', $exportOutput);
    }

    private function handleUnpaidVacation(array &$output, array $billableFreeTime, ExportOutput $exportOutput): float
    {
        return $this->calculateAbsences($output, $billableFreeTime, 'Neplacené volno', $exportOutput);
    }

    private function handleNursing(array &$output, array $billableFreeTime, ExportOutput $exportOutput): float
    {
        return $this->calculateAbsences($output, $billableFreeTime, 'Ošetřování člena rodiny', $exportOutput);
    }

    private function handleSickness(array &$output, array $sickness, ExportOutput $exportOutput): float
    {
        $absenceHours = 0;
        /** @var Absence $absence */
        foreach ($sickness as $absence) {
            $row = $this->handleAbsence($absence, $output);
            $day = $this->getDayInMonth($absence->getDay(), $exportOutput->getMonth(), $exportOutput->getYear());
            if(!$this->checkWeekend($row,$day)){
                $row->setNote('Nemocenská');
                $absenceHours += $absence->getValue();
            }

        }

        return $absenceHours;

    }

    private function calculateAbsences(array &$output, array $absences, string $note, ExportOutput $exportOutput): float
    {
        $absenceHours = 0;
        /** @var Absence $absence */
        foreach ($absences as $absence) {
            $row = $this->handleAbsence($absence, $output);
            $day = $this->getDayInMonth($absence->getDay(), $exportOutput->getMonth(), $exportOutput->getYear());
            if(!$this->checkHolidayOrWeekend($row,$day)){
                $row->setNote($note);
                $absenceHours += $absence->getValue();
            }

        }

        return $absenceHours;
    }


    private function handleAbsence(Absence $absence, array &$output): ExportRow
    {
        if (!array_key_exists($absence->getDay(), $output)) {
            $row = new ExportRow($absence->getDay());
            $output[$absence->getDay()] = $row;
        } else {
            $row = $output[$absence->getDay()];
        }

        return $row;
    }

    private function getDaysWithHolidays(int $month, int $year): Month
    {
        $start_date = new DateTime("01-{$month}-{$year}");
        $end_date = new DateTime("01-{$month}-{$year}");
        $end_date->modify("+1 month");
        $daysInMonth = [];
        $numberOfWorkingDays = 0;

        for ($cnt = 0; $start_date < $end_date; $cnt++) {
            $day = $this->getDayInMonth($cnt+1, $month, $year);
            if($day->isWeekend()){
                $numberOfWorkingDays++;
            }
            $daysInMonth[]=$day;
            $start_date->modify('+1 day');
        }

        return new Month($daysInMonth, $numberOfWorkingDays);
    }


    private function computeHoursWorked(ExportRow &$row, int &$worked, Shift $shift): void
    {
        $workInterval = $shift->getWorkStart()->diff($shift->getWorkEnd());

        // Subtract break if both Start and End is defined, otherwise ignore it!
        if ($shift->getBreakStart() !== null && $shift->getBreakEnd() !== null) {
            $breakInterval = $shift->getBreakStart()->diff($shift->getBreakEnd());
            $workInterval = $this->subtractDateIntervals($workInterval, $breakInterval);
        }

        $hoursWorked = $this->calculateMinutes($workInterval) / 60;
        $row->setHoursWorked($hoursWorked);
        $worked += $hoursWorked;
    }

    private function getDayInMonth(int $day, int $month, int $year): DayInMonth
    {
        $isHoliday = false;
        /** @var Holiday $holiday */
        foreach ($this->holidays as $holiday) {
            if ($holiday->getDay() == $day && $holiday->getMonth() == $month) {
                $isHoliday = true;
            }
        }
        $isWeekend = in_array(date("l", strtotime("{$year}-{$month}-{$day}")), ["Saturday", "Sunday"], true);
        return new DayInMonth($isHoliday, $isWeekend);
    }

    private function checkHolidayOrWeekend(ExportRow &$row, DayInMonth $dayInMonth): bool
    {
        if ($dayInMonth->isWeekend()) {

            $row->setNote('Víkend');
            return true;

        } else if ($dayInMonth->isHoliday()) {

            $row->setNote('Státní Svátek');
            return true;

        } else {
            $row->setDarkRow(false);
            return false;
        }
    }

    private function checkWeekend(ExportRow &$row, DayInMonth $day): bool
    {
        if ($day->isWeekend()){
            $row->setNote('Víkend');
            return true;
        } else{
            $row->setDarkRow(false);
            return false;
        }
    }

    private function subtractDateIntervals(DateInterval $a, DateInterval $b): DateInterval
    {
        $reference = new DateTimeImmutable;
        $endTime = clone $reference;

        $endTime = $endTime->add($a);
        $endTime = $endTime->sub($b);

        return $reference->diff($endTime);
    }

    private function calculateMinutes(DateInterval $int): int
    {
        $days = $int->format('%a');
        return ($days * 24 * 60) + ($int->h * 60) + $int->i;
    }
}