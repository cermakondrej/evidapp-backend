<?php

declare(strict_types=1);

namespace EvidApp\User\Domain\Specification;

use EvidApp\User\Domain\ValueObject\Email;

interface UniqueEmailSpecificationInterface
{
    public function isUnique(Email $email): bool;
}