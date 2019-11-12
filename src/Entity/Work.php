<?php
declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WorkRepository")
 */
class Work implements JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="float")
     * @var float
     */
    private $workload;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="time")
     * @var DateTimeInterface
     */
    private $start;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="time", nullable=true)
     * @var DateTimeInterface
     */
    private $breakStart;

    /**
     * @ORM\Column(type="time", nullable=true)
     * @var DateTimeInterface
     */
    private $breakEnd;

    /**
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity="App\Entity\Job", inversedBy="works")
     * @ORM\JoinColumn(nullable=false)
     * @var Job
     */
    private $job;

    /**
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="works")
     * @ORM\JoinColumn(nullable=false)
     * @var Company
     */
    private $company;

    /**
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="works")
     * @ORM\JoinColumn(nullable=false)
     * @var User
     */
    private $employee;

    public function __toString(): string
    {
        return $this->employee->getFullName() . " - "
            . $this->getCompany()->getName()
            . " - " . $this->getJob()->getName()
            . " (" . $this->getWorkload() . ")";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorkload(): ?float
    {
        return $this->workload;
    }

    public function setWorkload(float $workload): self
    {
        $this->workload = $workload;

        return $this;
    }

    public function getStart(): ?DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getBreakStart(): DateTimeInterface
    {
        return $this->breakStart;
    }

    public function setBreakStart(DateTimeInterface $breakStart): self
    {
        $this->breakStart = $breakStart;

        return $this;
    }

    public function getBreakEnd(): DateTimeInterface
    {
        return $this->breakEnd;
    }

    public function setBreakEnd(DateTimeInterface $breakEnd): self
    {
        $this->breakEnd = $breakEnd;

        return $this;
    }

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function setJob(?Job $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

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

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'workload' => $this->workload,
            'start' => $this->start->format('H:i'),
            'break_start' => $this->breakStart->format('H:i'),
            'break_end' => $this->breakEnd->format('H:i'),
            'job' => $this->job->getName(),
            'company' => $this->company->getName(),
            'employee' => $this->employee
        ];
    }
}
