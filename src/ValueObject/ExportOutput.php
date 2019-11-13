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

    /**
     * @param string $fullName
     */
    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    /**
     * @param string $jobName
     */
    public function setJobName(string $jobName): void
    {
        $this->jobName = $jobName;
    }

    /**
     * @param float $workload
     */
    public function setWorkload(float $workload): void
    {
        $this->workload = $workload;
    }

    /**
     * @param string $companyName
     */
    public function setCompanyName(string $companyName): void
    {
        $this->companyName = $companyName;
    }

    /**
     * @param int $month
     */
    public function setMonth(int $month): void
    {
        $this->month = $month;
    }

    /**
     * @param int $year
     */
    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    /**
     * @param float $workHours
     */
    public function setWorkHours(float $workHours): void
    {
        $this->workHours = $workHours;
    }

    /**
     * @param float $totalWorked
     */
    public function setTotalWorked(float $totalWorked): void
    {
        $this->totalWorked = $totalWorked;
    }

    /**
     * @param float $totalVacation
     */
    public function setTotalVacation(float $totalVacation): void
    {
        $this->totalVacation = $totalVacation;
    }

    /**
     * @param float $totalSickness
     */
    public function setTotalSickness(float $totalSickness): void
    {
        $this->totalSickness = $totalSickness;
    }

    /**
     * @param float $totalUnpaidVacation
     */
    public function setTotalUnpaidVacation(float $totalUnpaidVacation): void
    {
        $this->totalUnpaidVacation = $totalUnpaidVacation;
    }

    /**
     * @param float $totalNursing
     */
    public function setTotalNursing(float $totalNursing): void
    {
        $this->totalNursing = $totalNursing;
    }

    /**
     * @param float $totalBillableFreeTime
     */
    public function setTotalBillableFreeTime(float $totalBillableFreeTime): void
    {
        $this->totalBillableFreeTime = $totalBillableFreeTime;
    }

    /**
     * @param float $totalHours
     */
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