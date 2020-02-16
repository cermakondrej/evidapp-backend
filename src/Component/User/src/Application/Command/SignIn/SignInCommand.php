<?php

declare(strict_types=1);

namespace EvidApp\User\Application\Command\SignIn;

use EvidApp\User\Domain\ValueObject\Email;
use EvidApp\Shared\Infrastructure\Bus\CommandInterface;

class SignInCommand implements CommandInterface
{
    /** @var Email */
    public $email;

    /** @var string */
    public $plainPassword;

    public function __construct(string $email, string $plainPassword)
    {
        $this->email = Email::fromString($email);
        $this->plainPassword = $plainPassword;
    }
}