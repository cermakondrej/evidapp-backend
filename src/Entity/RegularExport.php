<?php

declare(strict_types=1);

namespace App\Entity;

use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RegularExportRepository")
 */
class RegularExport extends WorkExport implements JsonSerializable
{

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
            'month' => $this->getMonth()
        ];
    }
}