<?php

declare(strict_types=1);

namespace App\Entity;

use App\ValueObject\Absence\BillableFreeTime;
use App\ValueObject\Absence\Nursing;
use App\ValueObject\Absence\Sickness;
use App\ValueObject\Absence\UnpaidVacation;
use App\ValueObject\Absence\Vacation;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"employee" = "EmployeeExport", "variable" = "VariableExport"})
 */
class WorkExport
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $jobName;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="exports")
     * @ORM\JoinColumn(nullable=false)
     * @var User
     */
    private $employee;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Work", inversedBy="exports")
     * @ORM\JoinColumn(nullable=false)
     * @var Work
     */
    private $work;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @var Vacation[]
     */
    private $vacation = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     * @var Sickness[]
     */
    private $sickness = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     * @var UnpaidVacation[]
     */
    private $unpaidVacation = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     * @var Nursing[]
     */
    private $nursing = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     * @var BillableFreeTime[]
     */
    private $billableFreeTime = [];

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $year;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $month;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJobName(): ?string
    {
        return $this->jobName;
    }

    public function setJobName(?string $jobName): self
    {
        $this->jobName = $jobName;

        return $this;
    }

    public function getEmployee(): ?User
    {
        return $this->employee;
    }

    public function setEmployee(?User $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    public function getWork(): ?Work
    {
        return $this->work;
    }

    public function setWork(?Work $work): self
    {
        $this->work = $work;

        return $this;
    }

    public function getVacation(): ?array
    {
        return $this->vacation;
    }

    public function setVacation(?array $vacation): self
    {
        $this->vacation = $vacation;

        return $this;
    }

    public function getSickness(): ?array
    {
        return $this->sickness;
    }

    public function setSickness(array $sickness): self
    {
        $this->sickness = $sickness;

        return $this;
    }

    public function getUnpaidVacation(): ?array
    {
        return $this->unpaidVacation;
    }

    public function setUnpaidVacation(?array $unpaidVacation): self
    {
        $this->unpaidVacation = $unpaidVacation;

        return $this;
    }

    public function getNursing(): ?array
    {
        return $this->nursing;
    }

    public function setNursing(?array $nursing): self
    {
        $this->nursing = $nursing;

        return $this;
    }

    public function getBillableFreeTime(): ?array
    {
        return $this->billableFreeTime;
    }

    public function setBillableFreeTime(?array $billableFreeTime): self
    {
        $this->billableFreeTime = $billableFreeTime;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getMonth(): ?int
    {
        return $this->month;
    }

    public function setMonth(int $month): self
    {
        $this->month = $month;

        return $this;
    }
}
