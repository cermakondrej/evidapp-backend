<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Work;
use App\Repository\WorkRepository;
use App\Validation\RequestValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("works")
 */
class WorkController extends BaseController
{
    /**
     * @Route("/", name="work_list", methods={"GET"})
     */
    public function listAction(WorkRepository $repository, Request $request): JsonResponse
    {
        // TODO EXTRACT THIS INTO SOME KIND OF MIDDLEWARE
        $limit = (int) $request->query->get('limit') ?? 25;
        $page = (int) $request->query->get('page') ?? 1;

        return $this->respond($repository->findAll());
    }

    /**
     * @Route("/{id}", name="work_detail", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function detailAction(Work $work): JsonResponse
    {
        return $this->respondWithResource($work);
    }

    /**
     * @Route("/", name="work_new", methods={"POST"})
     */
    public function newAction(Request $request, RequestValidator $validator): JsonResponse
    {

        try {
            $object = $validator->validate((string)$request->getContent(), Work::class);
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->respondCreated($object);
        } catch (BadRequestHttpException $e) {
            return $this->respondInvalidRequest($e->getMessage());
        }
    }


    /**
     * @Route("/{id}", name="work_edit", methods={"PATCH", "PUT"}, requirements={"id":"\d+"})
     */
    public function editAction(Request $request, RequestValidator $validator, Work $work): JsonResponse
    {
        try {
            $object = $validator->validate((string)$request->getContent(), Work::class, $work);

            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->respondWithResource($object);
        } catch (BadRequestHttpException $e) {
            return $this->respondInvalidRequest($e->getMessage());
        }
    }

    /**
     * @Route("/{id}", name="work_delete", methods={"DELETE"}, requirements={"id":"\d+"})
     */
    public function deleteAction(Work $work): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($work);
        $em->flush();

        return $this->respondDeleted();
    }
}
