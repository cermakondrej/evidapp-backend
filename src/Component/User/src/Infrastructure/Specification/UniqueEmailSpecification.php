<?php

declare(strict_types=1);

namespace EvidApp\User\Infrastructure\Specification;

use EvidApp\Shared\Domain\Specification\AbstractSpecification;
use EvidApp\User\Domain\Exception\EmailAlreadyExistsException;
use EvidApp\User\Domain\Repository\CheckUserByEmailInterface;
use EvidApp\User\Domain\Specification\UniqueEmailSpecificationInterface;
use EvidApp\User\Domain\ValueObject\Email;
use Doctrine\ORM\NonUniqueResultException;

final class UniqueEmailSpecification extends AbstractSpecification implements UniqueEmailSpecificationInterface
{
    private CheckUserByEmailInterface $checkUserByEmail;

    public function __construct(CheckUserByEmailInterface $checkUserByEmail)
    {
        $this->checkUserByEmail = $checkUserByEmail;
    }

    public function isUnique(Email $email): bool
    {
        return $this->isSatisfiedBy($email);
    }

    public function isSatisfiedBy($value): bool
    {
        try {
            if ($this->checkUserByEmail->existsEmail($value)) {
                throw new EmailAlreadyExistsException();
            }
        } catch (NonUniqueResultException $e) {
            throw new EmailAlreadyExistsException();
        }

        return true;
    }
}