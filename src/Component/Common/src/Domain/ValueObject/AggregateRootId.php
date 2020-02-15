<?php


namespace EvidApp\Common\Domain\ValueObject;

use Ramsey\Uuid\Uuid;

abstract class AggregateRootId
{
    /**
     * @var string
     */
    protected $uuid;

    public function __construct(?string $id = null)
    {
        try {

            $this->uuid = Uuid::fromString($id ?: (string) Uuid::uuid4())->toString();

        } catch (\InvalidArgumentException $e) {

            throw new InvalidUUIDException();
        }
    }

    public function equals(AggregateRootId $aggregateRootId): bool
    {
        return $this->uuid === $aggregateRootId->__toString();
    }

    public function bytes(): string
    {
        return Uuid::fromString($this->uuid)->getBytes();
    }

    public static function fromBytes(string $bytes): self
    {
        return new static(Uuid::fromBytes($bytes)->toString());
    }

    public static function toBytes(string $uid): string
    {
        return (new static($uid))->bytes();
    }

    public function __toString(): string
    {
        return (string) $this->uuid;
    }
}