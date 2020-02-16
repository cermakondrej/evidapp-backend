<?php

declare(strict_types=1);

namespace EvidApp\User\Domain\Event;

use EvidApp\Shared\Domain\ValueObject\DateTime;
use EvidApp\User\Domain\ValueObject\Auth\Credentials;
use EvidApp\User\Domain\ValueObject\Auth\HashedPassword;
use EvidApp\User\Domain\ValueObject\Email;
use Assert\Assertion;
use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class UserWasCreated implements Serializable
{

    /** @var UuidInterface */
    public $uuid;

    /** @var Credentials */
    public $credentials;

    /** @var DateTime */
    public $createdAt;

    public function __construct(UuidInterface $uuid, Credentials $credentials, DateTime $createdAt)
    {
        $this->uuid = $uuid;
        $this->credentials = $credentials;
        $this->createdAt = $createdAt;
    }

    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'uuid');
        Assertion::keyExists($data, 'credentials');

        return new self(
            Uuid::fromString($data['uuid']),
            new Credentials(
                Email::fromString($data['credentials']['email']),
                HashedPassword::fromHash($data['credentials']['password'])
            ),
            DateTime::fromString($data['created_at'])
        );
    }

    public function serialize(): array
    {
        return [
            'uuid' => $this->uuid->toString(),
            'credentials' => [
                'email' => $this->credentials->email()->toString(),
                'password' => $this->credentials->password()->toString(),
            ],
            'created_at' => $this->createdAt->toString(),
        ];
    }
}