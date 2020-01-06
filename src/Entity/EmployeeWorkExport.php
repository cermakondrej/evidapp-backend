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
