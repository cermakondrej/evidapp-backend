<?php

declare(strict_types=1);

namespace EvidApp\User\Domain\Event;

use EvidApp\Shared\Domain\ValueObject\DateTime;
use EvidApp\User\Domain\ValueObject\Email;
use Assert\Assertion;
use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class UserEmailChanged implements Serializable
{
    public UuidInterface $uuid;
    public Email $email;
    public DateTime $updatedAt;

    public function __construct(UuidInterface $uuid, Email $email, DateTime $updatedAt)
    {
        $this->email = $email;
        $this->uuid = $uuid;
        $this->updatedAt = $updatedAt;
    }

    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'uuid');
        Assertion::keyExists($data, 'email');

        return new self(
            Uuid::fromString($data['uuid']),
            Email::fromString($data['email']),
            DateTime::fromString($data['updated_at'])
        );
    }

    public function serialize(): array
    {
        return [
            'uuid' => $this->uuid->toString(),
            'email' => $this->email->toString(),
            'updated_at' => $this->updatedAt->toString(),
        ];
    }
}