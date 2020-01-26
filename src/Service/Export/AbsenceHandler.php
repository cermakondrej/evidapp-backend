<?php
declare(strict_types=1);

namespace App\Service\Export;

use App\DTO\ExportOutput;
use App\DTO\ExportRow;
use App\Entity\VariableWorkExport;
use App\Entity\WorkExport;
use App\Factory\DTO\ExportRowFactory;
use App\Factory\ValueObject\DayInMonthFactory;
use App\ValueObject\Absence;
use App\ValueObject\DayInMonth;

class AbsenceHandler
{
    /** @var DayInMonthFactory */
    private $dayInMonthFactory;

    /** @var ExportRowFactory */
    private $exportRowFactory;

    public function __construct(DayInMonthFactory $dayInMonthFactory, ExportRowFactory $exportRowFactory)
    {
        $this->dayInMonthFactory = $dayInMonthFactory;
        $this->exportRowFactory = $exportRowFactory;
    }


    public function handleAbsences(array &$output, ExportOutput &$exportOutput, WorkExport $export): float
    {
        $totalBillable = $this->handleBillableFreeTime($output, $export->getBillableFreeTime(), $exportOutput);
        $totalVacation = $this->handleVacation($output, $export->getVacation(), $exportOutput);
        $totalUnpaidVacation = $this->handleUnpaidVacation($output, $export->getUnpaidVacation(), $exportOutput);
        $totalNursing = $this->handleNursing($output, $export->getNursing(), $exportOutput);
        $totalSickness = $this->handleSickness($output, $export->getSickness(), $exportOutput);

        $exportOutput->setTotalBillableFreeTime(number_format($totalBillable, 2));
        $exportOutput->setTotalVacation(number_format($totalVacation, 2));
        $exportOutput->setTotalUnpaidVacation(number_format($totalUnpaidVacation, 2));
        $exportOutput->setTotalNursing(number_format($totalNursing, 2));
        $exportOutput->setTotalSickness(number_format($totalSickness, 2));

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

    private function checkWeekend(ExportRow &$row, DayInMonth $day): bool
    {
        if ($day->isWeekend()) {
            $row->setNote('Víkend');
            return true;
        }

        $row->setDarkRow(false);
        return false;
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



}