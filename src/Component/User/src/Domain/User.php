<?php

declare(strict_types=1);

namespace EvidApp\User\Domain;

use EvidApp\Shared\Domain\ValueObject\DateTime;
use EvidApp\User\Domain\Event\UserEmailChanged;
use EvidApp\User\Domain\Event\UserPasswordChanged;
use EvidApp\User\Domain\Event\UserSignedIn;
use EvidApp\User\Domain\Event\UserWasCreated;
use EvidApp\User\Domain\Exception\InvalidCredentialsException;
use EvidApp\User\Domain\Specification\UniqueEmailSpecificationInterface;
use EvidApp\User\Domain\ValueObject\Auth\Credentials;
use EvidApp\User\Domain\ValueObject\Auth\HashedPassword;
use EvidApp\User\Domain\ValueObject\Email;
use Assert\Assertion;
use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Ramsey\Uuid\UuidInterface;

class User extends EventSourcedAggregateRoot
{
    private UuidInterface $uuid;
    private Email $email;
    private HashedPassword $hashedPassword;
    private DateTime $createdAt;
    private ?DateTime $updatedAt;

    public static function create(
        UuidInterface $uuid,
        Credentials $credentials,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ): self
    {
        $uniqueEmailSpecification->isUnique($credentials->getEmail());

        $user = new self();

        $user->apply(new UserWasCreated($uuid, $credentials, DateTime::now()));

        return $user;
    }

    public function changeEmail(
        Email $email,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ): void
    {
        $uniqueEmailSpecification->isUnique($email);
        $this->apply(new UserEmailChanged($this->uuid, $email, DateTime::now()));
    }

    public function changePassword(HashedPassword $password): void
    {
        $this->apply(new UserPasswordChanged($this->uuid, $password, DateTime::now()));
    }

    public function signIn(string $plainPassword): void
    {
        if (!$this->hashedPassword->match($plainPassword)) {
            throw new InvalidCredentialsException('Invalid credentials entered.');
        }

        $this->apply(new UserSignedIn($this->uuid, $this->email));
    }

    protected function applyUserWasCreated(UserWasCreated $event): void
    {
        $this->uuid = $event->uuid;

        $this->setEmail($event->credentials->getEmail());
        $this->setHashedPassword($event->credentials->getPassword());
        $this->setCreatedAt($event->createdAt);
    }

    protected function applyUserEmailChanged(UserEmailChanged $event): void
    {
        Assertion::notEq($this->email->toString(), $event->email->toString(), 'New email should be different');

        $this->setEmail($event->email);
        $this->setUpdatedAt($event->updatedAt);
    }

    private function setEmail(Email $email): void
    {
        $this->email = $email;
    }

    private function setHashedPassword(HashedPassword $hashedPassword): void
    {
        $this->hashedPassword = $hashedPassword;
    }

    private function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    private function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function createdAt(): string
    {
        return $this->createdAt->toString();
    }

    public function updatedAt(): ?string
    {
        return isset($this->updatedAt) ? $this->updatedAt->toString() : null;
    }

    public function email(): string
    {
        return $this->email->toString();
    }

    public function uuid(): string
    {
        return $this->uuid->toString();
    }

    public function getAggregateRootId(): string
    {
        return $this->uuid->toString();
    }
}