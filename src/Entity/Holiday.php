<?php
declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HolidayRepository")
 */
class Holiday implements JsonSerializable
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
     * @ORM\Column(type="integer")
     * @var int
     */
    private $day;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="integer")
     * @var int
     */
    private $month;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?int
    {
        return $this->day;
    }

    public function setDay(int $day): void
    {
        $this->day = $day;
    }

    public function getMonth(): ?int
    {
        return $this->month;
    }

    public function setMonth(int $month): void
    {
        $this->month = $month;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'day' => $this->day,
            'month' => $this->month
        ];
    }
}
