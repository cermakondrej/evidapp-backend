<?php

declare(strict_types=1);

namespace App\Service\Export;

use App\Entity\VariableExport;
use App\Entity\WorkExport;
use App\ValueObject\Absence\BillableFreeTime;
use App\ValueObject\DayInMonth;
use App\ValueObject\ExportOutput;
use App\ValueObject\ExportRow;
use App\ValueObject\Month;
use App\ValueObject\Shift;
use DateTimeImmutable;
use DateInterval;

class VariableWorkExporter implements WorkExporter
{

    public function createExport(VariableExport $export): ExportOutput
    {
        $output = $this->generateHeaderFromExport($export);

        $month = $this->getDaysWithHolidays($export->getMonth());

        $exportRows = [];
        /** @var Shift $shift */
        foreach ($export->getShifts() as $shift) {
            $outputRow = new ExportRow($shift->getDay());

            $outputRow->setWorkStart($shift->getWorkStart());
            $outputRow->setWorkEnd($shift->getWorkEnd());
            $outputRow->setBreakStart($shift->getWorkStart());
            $outputRow->setBreakEnd($shift->getBreakEnd());

            $this->computeHoursWorked($outputRow, $shift);
            $dayInMonth = $this->getDayInMonth($shift->getDay(), $export->getMonth(), $export->getYear());
            $this->checkHolidayOrWeekend($outputRow, $dayInMonth);
            $exportRows[$shift->getDay()] = $outputRow;
        }


        $this->handleAbsences($exportRows, $export);

        //TODO fill in rest of export rows with weekdays, holidays or blank lines
        $this->fillRest($exportRows);

        $output->setWorkHours($month->getNumberOfWorkingDays() * $export->getWork()->getWorkload());


        return $output;
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

    private function handleAbsences(array &$output, WorkExport $export): void
    {
        $this->handleBillableFreeTime($output, $export->getBillableFreeTime());
        $this->handleVacation($output, $export->getVacation());
        $this->handleUnpaidVacation($output, $export->getUnpaidVacation());
        $this->handleNursing($output, $export->getNursing());
        $this->handleSickness($output, $export->getSickness());
    }

    private function handleBillableFreeTime(array &$output, array $billableFreeTime): void
    {
        /** @var BillableFreeTime $b */
        foreach ($billableFreeTime as $b) {
            // TODO
        }
    }

    private function handleVacation(array &$output, array $billableFreeTime): void
    {
        // TODO
    }

    private function handleUnpaidVacation(array &$output, array $billableFreeTime): void
    {
        // TODO
    }

    private function handleNursing(array &$output, array $billableFreeTime): void
    {
        // TODO
    }

    private function handleSickness(array &$output, array $billableFreeTime): void
    {
        //Overrule holiday but not weekday
    }

    private function getDaysWithHolidays(int $month): Month
    {
        // TODO
    }

    private function computeHoursWorked(ExportRow &$row, Shift $shift): void
    {
        $workInterval = $shift->getWorkStart()->diff($shift->getBreakEnd());

        // Subtract break if both Start and End is defined, otherwise ignore it!
        if ($shift->getBreakStart() !== null && $shift->getBreakEnd() !== null) {
            $breakInterval = $shift->getBreakStart()->diff($shift->getBreakEnd());
            $workInterval = $this->subtractDateIntervals($workInterval, $breakInterval);
        }

        $hoursWorked = $this->calculateMinutes($workInterval) / 60;
        $row->setHoursWorked($hoursWorked);
    }

    private function getDayInMonth(int $day, int $month, int $year): DayInMonth
    {
        // TODO
    }

    private function checkHolidayOrWeekend(ExportRow &$row, DayInMonth $dayInMonth): void
    {
        if ($dayInMonth->isWeekend()) {

            $row->setNote('Víkend');

        } else if ($dayInMonth->isHoliday()) {

            $row->setNote('Státní Svátek');

        } else {
            $row->setDarkRow(false);
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