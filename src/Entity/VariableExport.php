<?php

declare(strict_types=1);

namespace App\Entity;

use App\ValueObject\Shift;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VariableExportRepository")
 */
class VariableExport extends WorkExport implements JsonSerializable
{
    /**
     * @ORM\Column(type="array", nullable=true)
     * @var Shift[]
     */
    private $shifts = [];

    /**
     * @return Shift[]
     */
    public function getShifts(): array
    {
        return $this->shifts;
    }

    /**
     * @param Shift[] $shifts
     */
    public function setShifts(array $shifts): void
    {
        $this->shifts = $shifts;
    }


    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'job_name' => $this->getJobName(),
            'employee' => $this->getEmployee(),
            'vacation' => $this->getVacation(),
            'unpaid_vacation' => $this->getUnpaidVacation(),
            'sickness' => $this->getSickness(),
            'nursing' => $this->getNursing(),
            'billable_free_time' => $this->getBillableFreeTime(),
            'year' => $this->getYear(),
            'month' => $this->getMonth(),
            'shifts' => $this->getShifts(),
        ];
    }
}