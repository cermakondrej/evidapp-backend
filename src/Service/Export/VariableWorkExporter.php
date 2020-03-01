<?php

declare(strict_types=1);

namespace App\Service\Export;

use App\Entity\VariableWorkExport;
use App\Factory\DTO\ExportOutputFactory;
use App\Factory\DTO\ExportRowFactory;
use App\Factory\ValueObject\DayInMonthFactory;
use App\Factory\ValueObject\MonthFactory;
use App\Factory\ValueObject\ShiftFactory;
use App\ValueObject\DayInMonth;
use App\DTO\ExportOutput;
use App\DTO\ExportRow;
use App\ValueObject\Month;
use App\ValueObject\Shift;
use DateTimeImmutable;
use DateInterval;

class VariableWorkExporter
{

    /** @var ShiftFactory */
    private $shiftFactory;

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
        ShiftFactory $shiftFactory,
        AbsenceHandler $absenceHandler,
        DayInMonthFactory $dayInMonthFactory,
        MonthFactory $monthFactory,
        ExportRowFactory $exportRowFactory,
        ExportOutputFactory $exportOutputFactory
    ) {
        $this->shiftFactory = $shiftFactory;
        $this->absenceHandler = $absenceHandler;
        $this->dayInMonthFactory = $dayInMonthFactory;
        $this->monthFactory = $monthFactory;
        $this->exportRowFactory = $exportRowFactory;
        $this->exportOutputFactory = $exportOutputFactory;
    }


    public function createExport(VariableWorkExport $export): ExportOutput
    {
        $output = $this->exportOutputFactory->create($export);

        $exportRows = [];
        $hoursWorked = 0;

        $shifts = $this->shiftFactory->createMultiple($export->getShifts());

        foreach ($shifts as $shift) {
            $dayInMonth = $this->dayInMonthFactory->create($shift->getDay(), $export->getMonth(), $export->getYear());
            $outputRow = $this->exportRowFactory->create($shift);

            $worked = $this->computeHoursWorked($shift);
            $hoursWorked += $worked;
            // TODO extract all number_format into single function
            $outputRow->setHoursWorked(number_format($worked, 2));
            $this->checkHolidayOrWeekend($outputRow, $dayInMonth);

            $exportRows[$shift->getDay()] = $outputRow;
        }

        $month = $this->monthFactory->create($export->getMonth(), $export->getYear());
        $this->absenceHandler->handleAbsences($exportRows, $output, $export);


        $this->fillRest($exportRows, $month);

        $output->setWorkHours(number_format($month->getNumberOfWorkingDays() * $export->getWork()->getWorkload(), 2));

        $output->setTotalWorked(number_format($hoursWorked, 2));
        // order exportRows by key
        ksort($exportRows);
        $output->setExportRows($exportRows);

        return $output;
    }


    private function fillRest(array &$exportRows, Month $month): void
    {
        /** @var DayInMonth $day */
        foreach ($month->getDaysInMonth() as $key => $day) {
            if (!array_key_exists($key + 1, $exportRows)) {
                $row = $this->exportRowFactory->createEmpty($key + 1);
                $this->checkHolidayOrWeekend($row, $day);
                $exportRows[$key + 1] = $row;
            }
        }
    }

    private function computeHoursWorked(Shift $shift): float
    {
        $workInterval = $shift->getWorkStart()->diff($shift->getWorkEnd());

        // Subtract break if both Start and End is defined, otherwise ignore it!
        if ($shift->getBreakStart() !== null && $shift->getBreakEnd() !== null) {
            $breakInterval = $shift->getBreakStart()->diff($shift->getBreakEnd());
            $workInterval = $this->subtractDateIntervals($workInterval, $breakInterval);
        }

        return $this->calculateMinutes($workInterval) / 60;
    }

    private function checkHolidayOrWeekend(ExportRow &$row, DayInMonth $dayInMonth): bool
    {
        if ($dayInMonth->isWeekend()) {
            $row->setNote('Víkend');
            return true;
        }

        if ($dayInMonth->isHoliday()) {
            $row->setNote('Státní Svátek');
            return true;
        }

        $row->setDarkRow(false);
        return false;
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
        $days = (int) $int->format('%a');
        return ($days * 24 * 60) + ($int->h * 60) + $int->i;
    }
}
