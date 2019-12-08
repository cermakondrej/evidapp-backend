<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\ValueObject\Shift;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VariableWorkExportRepository")
 */
class VariableWorkExport extends WorkExport implements JsonSerializable
{
    /**
     * @ORM\Column(type="json", nullable=true)
     * @var array
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
