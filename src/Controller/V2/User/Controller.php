<?php

declare(strict_types=1);

namespace App\Controller\V2\User;

use EvidApp\Shared\Application\Query\Item;
use EvidApp\Shared\Application\Query\Collection;
use EvidApp\User\Application\Command\ChangeEmail\ChangeEmailCommand;
use EvidApp\User\Application\Query\FindAll\FindAllQuery;
use App\Controller\V2\CommandQueryController;
use Assert\Assertion;
use EvidApp\User\Application\Query\FindByUuid\FindByUuidQuery;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class Controller extends CommandQueryController
{

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


        $query = new FindAllQuery((int) $page, (int) $limit);

        /** @var Collection $user */
        $response = $this->ask($query);

        return $this->jsonCollection($response, true);
    }

    /**
     * @Route(
     *     "/user/change-email/{uuid}",
     *     name="change_user_email",
     *     methods={"PATCH"}
     * )
     */
    public function changeEmailAction(Request $request, string $uuid): JsonResponse
    {
        Assertion::notNull($uuid, "Uuid can\'t be null");

        $email = $request->get('email');


        Assertion::notNull($email, "Email can\'t be null");

        $command = new ChangeEmailCommand($uuid, $email);

        $this->exec($command);

        return JsonResponse::create();
    }

}
