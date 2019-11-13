<?php

declare(strict_types=1);

namespace App\ValueObject;

use DateTimeInterface;

class Shift
{

    /** @var int */
    private $day;

    /** @var DateTimeInterface */
    private $workStart;

    /** @var DateTimeInterface */
    private $workEnd;

    /** @var DateTimeInterface|null */
    private $breakStart;

    /** @var DateTimeInterface|null */
    private $breakEnd;

    public function __construct(int $day, DateTimeInterface $workStart, DateTimeInterface $workEnd, DateTimeInterface $breakStart, DateTimeInterface $breakEnd)
    {
        $this->day = $day;
        $this->workStart = $workStart;
        $this->workEnd = $workEnd;
        $this->breakStart = $breakStart;
        $this->breakEnd = $breakEnd;
    }

    public function getDay(): int
    {
        return $this->day;
    }

    public function getWorkStart(): DateTimeInterface
    {
        return $this->workStart;
    }

    public function getWorkEnd(): DateTimeInterface
    {
        return $this->workEnd;
    }

    public function getBreakStart(): ?DateTimeInterface
    {
        return $this->breakStart;
    }

    public function getBreakEnd(): ?DateTimeInterface
    {
        return $this->breakEnd;
    }


}
