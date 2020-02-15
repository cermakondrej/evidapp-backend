<?php

declare(strict_types=1);

namespace EvidApp\Security\Domain\Exception;


class NullPasswordException extends \InvalidArgumentException
{

    public function __construct()
    {
        parent::__construct("security.exception.null_password", 6006);
    }
}