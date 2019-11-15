<?php

declare(strict_types=1);

namespace App\ValueObject;

use JsonSerializable;

class Absence implements JsonSerializable
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


    public function jsonSerialize(): array
    {
        return [
            "day" => $this->day,
            "value" => $this->value
        ];
    }
}