<?php

declare(strict_types=1);

namespace EvidApp\User\Domain\Exception;

use InvalidArgumentException;

class EmailAlreadyExistsException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Email already registered.');
    }
}
