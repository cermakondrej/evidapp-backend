<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\ValueObject\Shift;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VariableWorkExportRepository")
 */
class VariableWorkExport extends WorkExport
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
}
