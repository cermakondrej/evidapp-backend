<?php
declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="time", nullable=true)
     * @var DateTimeInterface|null
     */
    private $breakStart;

    /**
     * @ORM\Column(type="time", nullable=true)
     * @var DateTimeInterface|null
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WorkExport", mappedBy="work")
     * @var WorkExport[]
     */
    private $exports;

    public function __construct()
    {
        $this->exports = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getWorkload(): float
    {
        return $this->workload;
    }

    public function setWorkload(float $workload): void
    {
        $this->workload = $workload;
    }

    public function getStart(): DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(DateTimeInterface $start): void
    {
        $this->start = $start;
    }

    public function getBreakStart(): ?DateTimeInterface
    {
        return $this->breakStart;
    }

    public function setBreakStart(DateTimeInterface $breakStart): void
    {
        $this->breakStart = $breakStart;
    }

    public function getBreakEnd(): ?DateTimeInterface
    {
        return $this->breakEnd;
    }

    public function setBreakEnd(DateTimeInterface $breakEnd): void
    {
        $this->breakEnd = $breakEnd;
    }

    public function getJob(): Job
    {
        return $this->job;
    }

    public function setJob(Job $job): void
    {
        $this->job = $job;
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function setCompany(Company $company): void
    {
        $this->company = $company;
    }

    public function getEmployee(): User
    {
        return $this->employee;
    }

    public function setEmployee(User $employee): void
    {
        $this->employee = $employee;
    }

    /**
     * @return Collection|WorkExport[]
     */
    public function getExports(): Collection
    {
        return $this->exports;
    }

    public function addExport(WorkExport $export): void
    {
        if (!$this->exports->contains($export)) {
            $this->exports[] = $export;
            $export->setWork($this);
        }
    }

    public function removeExport(WorkExport $export): void
    {
        if ($this->exports->contains($export)) {
            $this->exports->removeElement($export);
            // set the owning side to null (unless already changed)
            if ($export->getWork() === $this) {
                $export->setWork(null);
            }
        }
    }

    private function getFormatedDate(?DateTimeInterface $date): ?string
    {
        if ($date !== null) {
            return $date->format('H:i');
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => "{$this->employee->getFullName()} - {$this->company->getName()} - {$this->job->getName()}" .
                " ({$this->workload})",
            'workload' => $this->workload,
            'start' => $this->getFormatedDate($this->start),
            'break_start' => $this->getFormatedDate($this->breakStart),
            'break_end' => $this->getFormatedDate($this->breakEnd),
            'job' => $this->job,
            'company' => $this->company,
            'employee' => $this->employee
        ];
    }
}
