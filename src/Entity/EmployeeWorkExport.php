<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;
use JMS\Serializer\Annotation\Type;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmployeeWorkExportRepository")
 */
class EmployeeWorkExport extends WorkExport
{

    /**
     * @ORM\Column(type="time")
     * @Type("DateTimeImmutable<'H:i'>")
     * @var DateTimeInterface
     */
    private $workStart;

    /**
     * @ORM\Column(type="time")
     * @Type("DateTimeImmutable<'H:i'>")
     * @var DateTimeInterface
     */
    private $breakStart;

    public function getWorkStart(): DateTimeInterface
    {
        return $this->workStart;
    }

    public function setWorkStart(DateTimeInterface $workStart): void
    {
        $this->workStart = $workStart;
    }

    public function getBreakStart(): DateTimeInterface
    {
        return $this->breakStart;
    }

    public function setBreakStart(DateTimeInterface $breakStart): void
    {
        $this->breakStart = $breakStart;
    }


}
