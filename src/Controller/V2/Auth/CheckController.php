<?php

declare(strict_types=1);

namespace App\Controller\V2\Auth;

use EvidApp\User\Application\Command\SignIn\SignInCommand;
use EvidApp\User\Application\Query\Auth\GetToken\GetTokenQuery;
use App\Controller\V2\CommandQueryController;
use Assert\Assertion;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class CheckController extends CommandQueryController
{
    /**
     * @Route(
     *     "/auth_check",
     *     name="auth_check",
     *     methods={"POST"},
     *     requirements={
     *      "_username": "\w+",
     *      "_password": "\w+"
     *     }
     * )
     */
    public function __invoke(Request $request): JsonResponse
    {
        $username = $request->get('_username');

        Assertion::notNull($username, 'Username cant\'t be empty');

        $signInCommand = new SignInCommand(
            $username,
            $request->get('_password')
        );

        $this->exec($signInCommand);

        return JsonResponse::create(
            [
                'token' => $this->ask(new GetTokenQuery($username)),
            ]
        );
    }
}