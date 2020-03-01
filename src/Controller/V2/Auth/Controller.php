<?php

declare(strict_types=1);

namespace App\Controller\V2\Auth;

use EvidApp\User\Application\Command\SignIn\SignInCommand;
use EvidApp\User\Application\Query\Auth\GetToken\GetTokenQuery;
use EvidApp\User\Application\Command\SignUp\SignUpCommand;
use App\Controller\V2\CommandQueryController;
use Assert\Assertion;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class Controller extends CommandQueryController
{
    /**
     * @Route(
     *     "/auth/check",
     *     name="auth_check",
     *     methods={"POST"}
     * )
     */
    public function checkAction(Request $request): JsonResponse
    {
        $username = $request->get('username');

        Assertion::notNull($username, 'Username cant\'t be empty');

        $signInCommand = new SignInCommand(
            $username,
            $request->get('password')
        );

        $this->exec($signInCommand);

        return JsonResponse::create(
            [
                'token' => $this->ask(new GetTokenQuery($username)),
            ]
        );
    }

    /**
     * @Route(
     *     "/auth/signup",
     *     name="auth_signup",
     *     methods={"POST"}
     * )
     */
    public function signUpAction(Request $request): JsonResponse
    {

        $uuid = $request->get('uuid');
        $email = $request->get('email');
        $plainPassword = $request->get('password');

        Assertion::notNull($uuid, "Uuid can\'t be null");
        Assertion::notNull($email, "Email can\'t be null");
        Assertion::notNull($plainPassword, "Password can\'t be null");

        $commandRequest = new SignUpCommand($uuid, $email, $plainPassword);

        $this->exec($commandRequest);

        return JsonResponse::create(null, JsonResponse::HTTP_CREATED);
    }
}
