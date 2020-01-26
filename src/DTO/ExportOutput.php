<?php

declare(strict_types=1);

namespace App\DTO;

use JMS\Serializer\Annotation\Type;

class ExportOutput
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

    /** @var string */
    private $workHours;

    /** @var string */
    private $totalWorked;

    /** @var string */
    private $totalVacation;

    /** @var string */
    private $totalSickness;

    /** @var string */
    private $totalUnpaidVacation;

    /** @var string */
    private $totalNursing;

    /** @var string */
    private $totalBillableFreeTime;

    /**
     * @Type("array<App\DTO\ExportRow>")
     * @var ExportRow[]
     */

    private $exportRows;

    public function __construct(
        string $fullName,
        string $jobName,
        float $workload,
        string $companyName,
        int $month,
        int $year
    ) {
        $this->fullName = $fullName;
        $this->jobName = $jobName;
        $this->workload = $workload;
        $this->companyName = $companyName;
        $this->month = $month;
        $this->year = $year;
    }


    public function getMonth(): int
    {
        return $this->month;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setWorkHours(string $workHours): void
    {
        $this->workHours = $workHours;
    }

    public function setTotalWorked(string $totalWorked): void
    {
        $this->totalWorked = $totalWorked;
    }

    public function setTotalVacation(string $totalVacation): void
    {
        $this->totalVacation = $totalVacation;
    }

    public function setTotalSickness(string $totalSickness): void
    {
        $this->totalSickness = $totalSickness;
    }

    public function setTotalUnpaidVacation(string $totalUnpaidVacation): void
    {
        $this->totalUnpaidVacation = $totalUnpaidVacation;
    }

    public function setTotalNursing(string $totalNursing): void
    {
        $this->totalNursing = $totalNursing;
    }

    public function setTotalBillableFreeTime(string $totalBillableFreeTime): void
    {
        $this->totalBillableFreeTime = $totalBillableFreeTime;
    }


    public function setExportRows(array $exportRows): void
    {
        $this->exportRows = $exportRows;
    }

}
