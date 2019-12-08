<?php

declare(strict_types=1);

namespace App\Entity;

use App\ValueObject\Absence;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"employee" = "EmployeeWorkExport", "variable" = "VariableWorkExport"})
 */
abstract class WorkExport
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
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
     * @ORM\Column(type="json")
     * @var Absence[]|array
     */
    private $vacation;

    /**
     * @ORM\Column(type="json")
     * @var Absence[]|array
     */
    private $sickness;

    /**
     * @ORM\Column(type="json")
     * @var Absence[]|array
     */
    private $unpaidVacation;

    /**
     * @ORM\Column(type="json")
     * @var Absence[]|array
     */
    private $nursing;

    /**
     * @ORM\Column(type="json")
     * @var Absence[]|array
     */
    private $billableFreeTime;

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

    public function __construct()
    {
        $this->vacation = [];
        $this->sickness = [];
        $this->unpaidVacation = [];
        $this->nursing = [];
        $this->billableFreeTime = [];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getJobName(): string
    {
        return $this->jobName;
    }

    public function setJobName(string $jobName): self
    {
        $this->jobName = $jobName;

        return $this;
    }

    public function getEmployee(): User
    {
        return $this->employee;
    }

    public function setEmployee(User $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    public function getWork(): Work
    {
        return $this->work;
    }

    public function setWork(Work $work): self
    {
        $this->work = $work;

        return $this;
    }

    /**
     * @return Absence[]|array
     */
    public function getVacation():array
    {
        return $this->vacation;
    }

    /**
     * @param Absence[]|array $vacation
     */
    public function setVacation(array $vacation): void
    {
        $this->vacation = $vacation;
    }

    /**
     * @return Absence[]|array
     */
    public function getSickness(): array
    {
        return $this->sickness;
    }

    /**
     * @param Absence[]|array $sickness
     */
    public function setSickness(array $sickness): void
    {
        $this->sickness = $sickness;
    }

    /**
     * @return Absence[]|array
     */
    public function getUnpaidVacation(): array
    {
        return $this->unpaidVacation;
    }

    /**
     * @param Absence[]|array $unpaidVacation
     */
    public function setUnpaidVacation(array $unpaidVacation): void
    {
        $this->unpaidVacation = $unpaidVacation;
    }

    /**
     * @return Absence[]|array
     */
    public function getNursing(): array
    {
        return $this->nursing;
    }

    /**
     * @param Absence[]|array $nursing
     */
    public function setNursing(array $nursing): void
    {
        $this->nursing = $nursing;
    }

    /**
     * @return Absence[]|array
     */
    public function getBillableFreeTime(): array
    {
        return $this->billableFreeTime;
    }

    /**
     * @param Absence[]|array $billableFreeTime
     */
    public function setBillableFreeTime($billableFreeTime): void
    {
        $this->billableFreeTime = $billableFreeTime;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getMonth(): int
    {
        return $this->month;
    }

    public function setMonth(int $month): self
    {
        $this->month = $month;

        return $this;
    }
}
