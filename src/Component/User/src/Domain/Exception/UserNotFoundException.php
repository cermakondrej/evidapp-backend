<?php

declare(strict_types=1);

namespace App\Component\User\Domain\Exception;

use RuntimeException;

class UserNotFoundException extends RuntimeException
{

    public function __construct()
    {
        parent::__construct("user.exception.not_found");
    }
}