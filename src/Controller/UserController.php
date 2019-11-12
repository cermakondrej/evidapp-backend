<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/user")
 */
class UserController extends BaseController
{
    /**
     * @Route("/{id}", name="api_user_detail", methods={"GET"})
     */
    public function detail(User $user): JsonResponse
    {
//        $this->denyAccessUnlessGranted('view', $user);

        return $this->respondWithResource($user);
    }


}
