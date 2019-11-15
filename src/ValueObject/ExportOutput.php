<?php

declare(strict_types=1);

namespace App\ValueObject;

use JsonSerializable;

class ExportOutput implements JsonSerializable
{
    /** @var string */
    private $fullName;

    /** @var string */
    private $jobName;

    /** @var float */
    private $workload;

    /** @var string */
    private $companyName;

    /** @var int */
    private $month;

    /** @var int */
    private $year;

    /** @var float */
    private $workHours;

    /** @var float */
    private $totalWorked;

    /** @var float */
    private $totalVacation;

    /** @var float */
    private $totalSickness;

    /** @var float */
    private $totalUnpaidVacation;

    /** @var float */
    private $totalNursing;

    /** @var float */
    private $totalBillableFreeTime;

    /** @var float */
    private $totalHours;

    /** @var ExportRow[] */
    private $exportRows;

    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function setJobName(string $jobName): void
    {
        $this->jobName = $jobName;
    }

    public function setWorkload(float $workload): void
    {
        $this->workload = $workload;
    }

    public function setCompanyName(string $companyName): void
    {
        $this->companyName = $companyName;
    }

    public function setMonth(int $month): void
    {
        $this->month = $month;
    }

    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    public function getMonth(): ?int
    {
        return $this->month;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setWorkHours(float $workHours): void
    {
        $this->workHours = $workHours;
    }

    public function setTotalWorked(float $totalWorked): void
    {
        $this->totalWorked = $totalWorked;
    }

    public function setTotalVacation(float $totalVacation): void
    {
        $this->totalVacation = $totalVacation;
    }

    public function setTotalSickness(float $totalSickness): void
    {
        $this->totalSickness = $totalSickness;
    }

    public function setTotalUnpaidVacation(float $totalUnpaidVacation): void
    {
        $this->totalUnpaidVacation = $totalUnpaidVacation;
    }

    public function setTotalNursing(float $totalNursing): void
    {
        $this->totalNursing = $totalNursing;
    }

    public function setTotalBillableFreeTime(float $totalBillableFreeTime): void
    {
        $this->totalBillableFreeTime = $totalBillableFreeTime;
    }

    public function setTotalHours(float $totalHours): void
    {
        $this->totalHours = $totalHours;
    }

    /**
     * @param ExportRow[] $exportRows
     */
    public function setExportRows(array $exportRows): void
    {
        $this->exportRows = $exportRows;
    }


    public function jsonSerialize(): array
    {
        return [
            'full_name' => $this->fullName,
            'job_name;' => $this->jobName,
            'workload' => $this->workload,
            'company_name' => $this->companyName,
            'month' => $this->month,
            'year' => $this->year,
            'work_hours' => $this->workHours,
            'total_worked' => $this->totalWorked,
            'total_vacation' => $this->totalVacation,
            'total_sickness' => $this->totalSickness,
            'total_unpaid_vacation' => $this->totalUnpaidVacation,
            'total_nursing' => $this->totalNursing,
            'total_billable_free_time' => $this->totalBillableFreeTime,
            'total_hours' => $this->totalHours,
            'export_rows' => $this->exportRows,
        ];
    }


}