<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Validation\RequestValidator;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/users")
 */
class UserController extends BaseController
{

    /**
     * @Route("/", name="user_list", methods={"GET"})
     */
    public function listAction(UserRepository $repository, Request $request): JsonResponse
    {

        return $this->respond($repository->findAll());
    }

    /**
     * @Route("/{id}", name="user_detail", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function detailAction(User $user): JsonResponse
    {
        return $this->respondWithResource($user);
    }


    /**
     * @Route("/", name="user_new", methods={"POST"})
     */
    public function newAction(
        Request $request,
        RequestValidator $validator,
        UserPasswordEncoderInterface $encoder
    ): JsonResponse {

        try {
            /** @var User $object */
            $object = $validator->validate((string)$request->getContent(), User::class);

            $object->setPassword($encoder->encodePassword($object, $object->getPassword()));

            // TODO check for constraints
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->respondCreated($object);
        } catch (BadRequestHttpException $e) {
            return $this->respondInvalidRequest($e->getMessage());
        }
    }


    /**
     * @Route("/{id}", name="user_edit", methods={"PATCH", "PUT"}, requirements={"id":"\d+"})
     */
    public function editAction(
        Request $request,
        RequestValidator $validator,
        User $user,
        UserPasswordEncoderInterface $encoder
    ): JsonResponse {
        try {
            $oldPass = $user->getPassword();

            /** @var User $object */
            $object = $validator->validate((string)$request->getContent(), User::class, $user);
            if ($oldPass !== $object->getPassword()) {
                $object->setPassword($encoder->encodePassword($object, $object->getPassword()));
            }
            // TODO check for constraint
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->respondWithResource($object);
        } catch (BadRequestHttpException $e) {
            return $this->respondInvalidRequest($e->getMessage());
        }
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"}, requirements={"id":"\d+"})
     */
    public function deleteAction(User $user): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->respondDeleted();
    }
}
