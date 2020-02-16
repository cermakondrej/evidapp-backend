<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Auth\Guard;

use EvidApp\User\Application\Command\SignIn\SignInCommand;
use EvidApp\Shared\Application\Query\Item;
use EvidApp\User\Application\Query\FindByEmail\FindByEmailQuery;
use EvidApp\User\Domain\Exception\InvalidCredentialsException;
use EvidApp\Shared\Infrastructure\Bus\CommandBus;
use EvidApp\Shared\Infrastructure\Bus\QueryBus;
use EvidApp\User\Infrastructure\Auth\Auth;
use EvidApp\User\Infrastructure\Query\Projections\UserView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

final class LoginAuthenticator extends AbstractFormLoginAuthenticator
{
    private const LOGIN = 'login';

    /** @var CommandBus */
    private $bus;

    /** @var QueryBus */
    private $queryBus;

    /** @var UrlGeneratorInterface */
    private $router;

    public function __construct(
        CommandBus $commandBus,
        QueryBus $queryBus,
        UrlGeneratorInterface $router
    ) {
        $this->bus = $commandBus;
        $this->router = $router;
        $this->queryBus = $queryBus;
    }

    public function supports(Request $request): bool
    {
        return $request->getPathInfo() === $this->router->generate(self::LOGIN) && $request->isMethod('POST');
    }

    public function getCredentials(Request $request): array
    {
        return [
            'email' => $request->request->get('_email'),
            'password' => $request->request->get('_password'),
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        try {
            $email = $credentials['email'];
            $plainPassword = $credentials['password'];

            $signInCommand = new SignInCommand($email, $plainPassword);

            $this->bus->handle($signInCommand);

            /** @var Item $userItem */
            $userItem = $this->queryBus->handle(new FindByEmailQuery($email));

            /** @var UserView $user */
            $user = $userItem->readModel;

            return Auth::create($user->uuid(), $user->email(), $user->hashedPassword());
        } catch (InvalidCredentialsException $exception) {
            throw new AuthenticationException();
        }
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        return null;
    }

    protected function getLoginUrl(): string
    {
        return $this->router->generate(self::LOGIN);
    }
}