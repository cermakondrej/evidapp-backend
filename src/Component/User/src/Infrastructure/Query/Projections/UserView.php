<?php

declare(strict_types=1);

namespace EvidApp\User\Infrastructure\Query\Projections;

use EvidApp\Shared\Domain\ValueObject\DateTime;
use EvidApp\User\Domain\ValueObject\Auth\Credentials;
use EvidApp\User\Domain\ValueObject\Auth\HashedPassword;
use EvidApp\User\Domain\ValueObject\Email;
use Broadway\ReadModel\SerializableReadModel;
use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UserView implements SerializableReadModel
{

    /** @var UuidInterface */
    private $uuid;

    /** @var Credentials */
    private $credentials;

    /** @var DateTime */
    private $createdAt;

    /** @var DateTime */
    private $updatedAt;


    public static function fromSerializable(Serializable $event): self
    {
        return self::deserialize($event->serialize());
    }

    public static function deserialize(array $data): self
    {
        $instance = new self();

        $instance->uuid = Uuid::fromString($data['uuid']);
        $instance->credentials = new Credentials(
            Email::fromString($data['credentials']['email']),
            HashedPassword::fromHash($data['credentials']['password'] ?? '')
        );

        $instance->createdAt = DateTime::fromString($data['created_at']);
        $instance->updatedAt = isset($data['updated_at']) ? DateTime::fromString($data['updated_at']) : null;

        return $instance;
    }

    public function serialize(): array
    {
        return [
            'uuid' => $this->uuid->toString(),
            'credentials' => [
                'email' => $this->credentials->email()->toString(),
            ],
        ];
    }

    public function uuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function email(): string
    {
        return $this->credentials->email()->toString();
    }

    public function hashedPassword(): string
    {
        return $this->credentials->password()->toString();
    }

    public function changeEmail(Email $email): void
    {
        $this->credentials = new Credentials($email, $this->credentials->password());
    }

    public function changeUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function changePassword(HashedPassword $password): void
    {
        $this->credentials = new Credentials($this->credentials->email(), $password);
    }

    public function getId(): string
    {
        return $this->uuid->toString();
    }
}
