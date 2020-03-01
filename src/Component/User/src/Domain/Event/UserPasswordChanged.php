<?php

declare(strict_types=1);

namespace EvidApp\User\Domain\Event;

use EvidApp\Shared\Domain\ValueObject\DateTime;
use EvidApp\User\Domain\ValueObject\Auth\HashedPassword;
use Assert\Assertion;
use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class UserPasswordChanged implements Serializable
{

    public UuidInterface $uuid;
    public HashedPassword $password;
    public DateTime $updatedAt;

    public function __construct(UuidInterface $uuid, HashedPassword $password, DateTime $updatedAt)
    {
        $this->password = $password;
        $this->uuid = $uuid;
        $this->updatedAt = $updatedAt;
    }

    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'uuid');
        Assertion::keyExists($data, 'password');

        return new self(
            Uuid::fromString($data['uuid']),
            HashedPassword::fromHash($data['email']),
            DateTime::fromString($data['updated_at'])
        );
    }

    public function serialize(): array
    {
        return [
            'uuid' => $this->uuid->toString(),
            'password' => $this->password->toString(),
            'updated_at' => $this->updatedAt->toString(),
        ];
    }
}