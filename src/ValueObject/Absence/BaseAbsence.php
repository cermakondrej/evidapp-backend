<?php

declare(strict_types=1);

namespace App\ValueObject\Absence;

class BaseAbsence
{
    /** @var int */
    private $day;

    /** @var float */
    private $value;

    public function __construct(int $day, float $value)
    {
        $this->day = $day;
        $this->value = $value;
    }

    public function getDay(): int
    {
        return $this->day;
    }

    public function getValue(): float
    {
        return $this->value;
    }




}