<?php

declare(strict_types=1);

namespace EvidApp\User\Domain\ValueObject\Auth;

use EvidApp\User\Domain\ValueObject\Email;

class Credentials
{
    private Email $email;
    private HashedPassword $password;

    public function __construct(Email $email, HashedPassword $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): HashedPassword
    {
        return $this->password;
    }
}
