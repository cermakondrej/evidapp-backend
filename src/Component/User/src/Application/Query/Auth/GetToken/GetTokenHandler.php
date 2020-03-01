<?php

declare(strict_types=1);

namespace EvidApp\User\Application\Query\Auth\GetToken;

use EvidApp\Shared\Application\Query\QueryHandlerInterface;
use EvidApp\User\Domain\Repository\GetUserCredentialsByEmailInterface;
use EvidApp\User\Infrastructure\Auth\AuthenticationProvider;

class GetTokenHandler implements QueryHandlerInterface
{
    private GetUserCredentialsByEmailInterface $userCredentialsByEmail;
    private AuthenticationProvider $authenticationProvider;

    public function __construct(
        GetUserCredentialsByEmailInterface $userCredentialsByEmail,
        AuthenticationProvider $authenticationProvider
    ) {
        $this->authenticationProvider = $authenticationProvider;
        $this->userCredentialsByEmail = $userCredentialsByEmail;
    }

    public function __invoke(GetTokenQuery $query): string
    {
        [$uuid, $email, $hashedPassword] = $this->userCredentialsByEmail->getCredentialsByEmail($query->email);

        return $this->authenticationProvider->generateToken($uuid, $email, $hashedPassword);
    }
}
