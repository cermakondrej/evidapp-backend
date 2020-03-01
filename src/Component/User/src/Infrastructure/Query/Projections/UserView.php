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
use JsonSerializable;

class UserView implements SerializableReadModel, JsonSerializable
{
    private UuidInterface $uuid;
    private Credentials $credentials;
    private DateTime $createdAt;
    private ?DateTime $updatedAt;


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
            'uuid' => $this->getId(),
            'credentials' => [
                'email' => $this->credentials->getEmail()->toString(),
            ],
        ];
    }

    public function uuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function email(): string
    {
        return $this->credentials->getEmail()->toString();
    }

    public function hashedPassword(): string
    {
        return $this->credentials->getPassword()->toString();
    }

    public function changeEmail(Email $email): void
    {
        $this->credentials = new Credentials($email, $this->credentials->getPassword());
    }

    public function changeUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function changePassword(HashedPassword $password): void
    {
        $this->credentials = new Credentials($this->credentials->getEmail(), $password);
    }

    public function getId(): string
    {
        return $this->uuid->toString();
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->getId(),
            'email' => $this->credentials->getEmail()->toString(),
            'created_at' => $this->createdAt->toString(),
            'updated_at' => $this->updatedAt ? $this->updatedAt->toString() : null,
        ];
    }
}
