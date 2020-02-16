<?php

declare(strict_types=1);

namespace EvidApp\Shared\Domain\ValueObject;

use EvidApp\Shared\Domain\Exception\DateTimeException;
use DateTimeImmutable;

class DateTime
{
    public const FORMAT = 'Y-m-d\TH:i:s.uP';

    /** @var DateTimeImmutable */
    private $dateTime;

    public static function now(): self
    {
        return self::create();
    }

    public static function fromString(string $dateTime): self
    {
        return self::create($dateTime);
    }

    private static function create(string $dateTime = ''): self
    {
        $self = new self();

        try {
            $self->dateTime = new DateTimeImmutable($dateTime);
        } catch (\Exception $e) {
            throw new DateTimeException($e);
        }

        return $self;
    }

    public function toString(): string
    {
        return $this->dateTime->format(self::FORMAT);
    }

    public function toNative(): DateTimeImmutable
    {
        return $this->dateTime;
    }

}