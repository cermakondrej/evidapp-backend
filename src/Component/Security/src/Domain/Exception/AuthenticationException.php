<?php

declare(strict_types=1);

namespace EvidApp\Security\Domain\Exception;

class AuthenticationException extends \Exception
{

    public function __construct()
    {
        parent::__construct('security.exception.authentication_exception');
    }
}
