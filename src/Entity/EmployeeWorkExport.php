<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmployeeWorkExportRepository")
 */
class EmployeeWorkExport extends WorkExport implements JsonSerializable
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
            'vacation' => $this->getVacationCollection(),
            'unpaid_vacation' => $this->getUnpaidVacationCollection(),
            'sickness' => $this->getSicknessCollection(),
            'nursing' => $this->getNursingCollection(),
            'billable_free_time' => $this->getBillableFreeTimeCollection(),
            'year' => $this->getYear(),
            'month' => $this->getMonth()
        ];
    }
}