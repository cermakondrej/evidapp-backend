<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\User;

use App\Application\Command\User\SignUp\SignUpCommand;
use EvidApp\Shared\Application\Query\Item;
use EvidApp\Shared\Application\Query\Collection;
use EvidApp\User\Application\Query\FindAll\FindALlQuery;
use EvidApp\User\Application\Query\FindByEmail\FindByEmailQuery;
use App\Controller\V2\CommandQueryController;
use Assert\Assertion;
use EvidApp\User\Application\Query\FindByEmail\FindByUuidQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class Controller extends CommandQueryController
{
    /**
     * @Route(
     *     "/user/{email}",
     *     name="find_user_email",
     *     methods={"GET"}
     * )
     */
    public function findByEmailAction(string $email): JsonResponse
    {
        Assertion::notNull($email, "Email can\'t be null");

        $query = new FindByEmailQuery($email);

        /** @var Item $user */
        $user = $this->ask($query);

        return $this->json($user);
    }

    /**
     * @Route(
     *     "/user/{uuid}",
     *     name="find_user_uuid",
     *     methods={"GET"}
     * )
     */
    public function findByUuidAction(string $uuid): JsonResponse
    {
        Assertion::notNull($uuid, "Uuid can\'t be null");

        $query = new FindByUuidQuery($uuid);

        /** @var Item $user */
        $user = $this->ask($query);

        return $this->json($user);
    }

    /**
     * @Route(
     *     "/user/",
     *     name="find_user_all",
     *     methods={"GET"}
     * )
     */
    public function findAllAction(Request $request): JsonResponse
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 999999);

        Assertion::numeric($page, 'Page number must be an integer');
        Assertion::numeric($limit, 'Limit results must be an integer');


        $query = new FindALlQuery((int) $page, (int) $limit);

        /** @var Collection $user */
        $response = $this->ask($query);

        return $this->jsonCollection($response, true);
    }

    /**
     * @Route(
     *     "/signup",
     *     name="user_create",
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
