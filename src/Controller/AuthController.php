<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/auth")
 */
class AuthController extends BaseController
{

    /**
     * @Route("/me", name="auth_me", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function meAction(): JsonResponse
    {
        if ($this->getUser() === null) {
            throw new UnauthorizedHttpException("Unauthorized");
        }

        return $this->respondWithResource($this->getUser());
    }
}
