<?php

declare(strict_types=1);

namespace App\Component\User\Domain\Exception;


class UserPasswordsDoNotMatchException
{
    public function __construct()
    {
        parent::__construct('user.exception.passwords-do-not-match');
    }
}