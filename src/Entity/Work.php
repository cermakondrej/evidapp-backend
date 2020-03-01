<?php
declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WorkRepository")
 */
class Work
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
     * @Type("DateTime<'H:i'>")
     * @Assert\NotBlank
     * @ORM\Column(type="time")
     * @var DateTimeImmutable
     */
    private $start;

    /**
     * @Type("DateTime<'H:i'>")
     * @ORM\Column(type="time", nullable=true)
     * @var DateTimeImmutable
     */
    private $breakStart;

    /**
     * @Type("DateTime<'H:i'>")
     * @ORM\Column(type="time", nullable=true)
     * @var DateTimeImmutable
     */
    private $breakEnd;

    /**
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity="App\Entity\Job")
     * @ORM\JoinColumn(nullable=false)
     * @var Job
     */
    private $job;

    /**
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity="App\Entity\Company")
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
     * @Exclude()
     * @var WorkExport[]|ArrayCollection
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

    /**
     * @VirtualProperty()
     */
    public function getName(): string
    {
        return "{$this->employee->getFullName()} - {$this->company->getName()} - {$this->job->getName()}" .
            " ({$this->workload})";
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

    public function setStart(DateTimeImmutable $start): void
    {
        $this->start = $start;
    }

    public function setBreakStart(DateTimeImmutable $breakStart): void
    {
        $this->breakStart = $breakStart;
    }

    public function setBreakEnd(DateTimeImmutable $breakEnd): void
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
}
