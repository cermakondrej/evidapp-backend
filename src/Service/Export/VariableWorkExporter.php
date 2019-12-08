<?php

declare(strict_types=1);

namespace App\Service\Export;

use App\Entity\WorkExport;
use App\Factory\DTO\ExportOutputFactory;
use App\Factory\DTO\ExportRowFactory;
use App\Factory\ValueObject\AbsenceFactory;
use App\Factory\ValueObject\DayInMonthFactory;
use App\Factory\ValueObject\MonthFactory;
use App\Factory\ValueObject\ShiftFactory;
use App\ValueObject\DayInMonth;
use App\ValueObject\Absence;
use App\DTO\ExportOutput;
use App\DTO\ExportRow;
use App\ValueObject\Month;
use App\ValueObject\Shift;
use DateTimeImmutable;
use DateInterval;

class VariableWorkExporter implements WorkExporterInterface
{

    /** @var ShiftFactory */
    private $shiftFactory;

    /** @var AbsenceFactory */
    private $absenceFactory;

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
        AbsenceFactory $absenceFactory,
        DayInMonthFactory $dayInMonthFactory,
        MonthFactory $monthFactory,
        ExportRowFactory $exportRowFactory,
        ExportOutputFactory $exportOutputFactory
    ) {
        $this->shiftFactory = $shiftFactory;
        $this->absenceFactory = $absenceFactory;
        $this->dayInMonthFactory = $dayInMonthFactory;
        $this->monthFactory = $monthFactory;
        $this->exportRowFactory = $exportRowFactory;
        $this->exportOutputFactory = $exportOutputFactory;
    }


    public function createExport(WorkExport $export): ExportOutput
    {
        $output = $this->exportOutputFactory->create($export);

        $exportRows = [];
        $hoursWorked = 0;
        $totalHours = 0;

        $shifts = $this->shiftFactory->createMultiple($export->getShifts());

        foreach ($shifts as $shift) {
            $dayInMonth = $this->dayInMonthFactory->create($shift->getDay(), $export->getMonth(), $export->getYear());
            $outputRow = $this->exportRowFactory->create($shift);

            $worked = $this->computeHoursWorked($shift);
            $hoursWorked += $worked;
            $outputRow->setHoursWorked($worked);
            $this->checkHolidayOrWeekend($outputRow, $dayInMonth);

            $exportRows[$shift->getDay()] = $outputRow;
        }

        $month = $this->monthFactory->create($export->getMonth(), $export->getYear());
        $totalHours += $this->handleAbsences($exportRows, $output, $export);


        $this->fillRest($exportRows, $month);

        $output->setWorkHours($month->getNumberOfWorkingDays() * $export->getWork()->getWorkload());

        $output->setTotalWorked($hoursWorked);
        $output->setTotalHours($totalHours);
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
            $day = $this->dayInMonthFactory->create(
                $absence->getDay(),
                $exportOutput->getMonth(),
                $exportOutput->getYear()
            );
            if (!$this->checkWeekend($row, $day)) {
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
            $day = $this->dayInMonthFactory->create(
                $absence->getDay(),
                $exportOutput->getMonth(),
                $exportOutput->getYear()
            );
            if (!$this->checkHolidayOrWeekend($row, $day)) {
                $row->setNote($note);
                $absenceHours += $absence->getValue();
            }
        }
        return $absenceHours;
    }


    private function handleAbsence(Absence $absence, array &$output): ExportRow
    {
        if (!array_key_exists($absence->getDay(), $output)) {
            $row = $this->exportRowFactory->createEmpty($absence->getDay());
            $output[$absence->getDay()] = $row;
            return $row;
        }

        $row = $output[$absence->getDay()];
        return $row;
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

    private function checkWeekend(ExportRow &$row, DayInMonth $day): bool
    {
        if ($day->isWeekend()) {
            $row->setNote('Víkend');
            return true;
        }

        $row->setDarkRow(false);
        return false;
    }

    // TODO Extract date helpers
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
