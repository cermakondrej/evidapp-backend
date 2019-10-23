<?php
declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WorkRepository")
 */
class Work
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="float")
     */
    private $workload;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="datetime")
     */
    private $start;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $break;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Job", inversedBy="works")
     * @ORM\JoinColumn(nullable=false)
     */
    private $job;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="works")
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="works")
     * @ORM\JoinColumn(nullable=false)
     */
    private $employee;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->employee->getFullName() . " - "
            . $this->getCompany()->getName()
            . " - " . $this->getJob()->getName()
            . " (" . $this->getWorkload() . ")";
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return float|null
     */
    public function getWorkload(): ?float
    {
        return $this->workload;
    }

    /**
     * @param float $workload
     * @return $this
     */
    public function setWorkload(float $workload): self
    {
        $this->workload = $workload;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getStart(): ?DateTimeInterface
    {
        return $this->start;
    }

    /**
     * @param DateTimeInterface $start
     * @return $this
     */
    public function setStart(DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getBreak(): ?DateTimeInterface
    {
        return $this->break;
    }

    /**
     * @param DateTimeInterface|null $break
     * @return $this
     */
    public function setBreak(?DateTimeInterface $break): self
    {
        $this->break = $break;

        return $this;
    }

    /**
     * @return Job|null
     */
    public function getJob(): ?Job
    {
        return $this->job;
    }

    /**
     * @param Job|null $job
     * @return $this
     */
    public function setJob(?Job $job): self
    {
        $this->job = $job;

        return $this;
    }

    /**
     * @return Company|null
     */
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    /**
     * @param Company|null $company
     * @return $this
     */
    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getEmployee(): ?User
    {
        return $this->employee;
    }

    /**
     * @param User|null $employee
     * @return $this
     */
    public function setEmployee(?User $employee): self
    {
        $this->employee = $employee;

        return $this;
    }
}
