<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 */
class Company implements JsonSerializable
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
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Work", mappedBy="company")
     * @var Work[]
     */
    private $works;

    public function __construct()
    {
        $this->works = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Collection|Work[]
     */
    public function getWorks(): Collection
    {
        return $this->works;
    }

    public function addWork(Work $work): void
    {
        if (!$this->works->contains($work)) {
            $this->works[] = $work;
            $work->setCompany($this);
        }
    }

    public function removeWork(Work $work): void
    {
        if ($this->works->contains($work)) {
            $this->works->removeElement($work);
            // set the owning side to null (unless already changed)
            if ($work->getCompany() === $this) {
                $work->setCompany(null);
            }
        }
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
