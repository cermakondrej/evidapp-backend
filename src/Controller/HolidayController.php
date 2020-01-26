<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Holiday;
use App\Repository\HolidayRepository;
use App\Validation\RequestValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("holidays")
 */
class HolidayController extends BaseController
{
    /**
     * @Route("/", name="holiday_list", methods={"GET"})
     */
    public function listAction(HolidayRepository $repository, Request $request): JsonResponse
    {

        return $this->respond($repository->findBy([
            'year' => $request->get('year')
        ]));
    }

    /**
     * @Route("/{id}", name="holiday_detail", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function detailAction(Holiday $holiday): JsonResponse
    {
        return $this->respondWithResource($holiday);
    }


    /**
     * @Route("/", name="holiday_new", methods={"POST"})
     */
    public function newAction(Request $request, RequestValidator $validator): JsonResponse
    {

        try {
            $object = $validator->validate((string)$request->getContent(), Holiday::class);

            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->respondCreated($object);
        } catch (BadRequestHttpException $e) {
            return $this->respondInvalidRequest($e->getMessage());
        }
    }

    /**
     * @Route("/{id}", name="holiday_edit", methods={"PATCH", "PUT"}, requirements={"id":"\d+"})
     */
    public function editAction(Request $request, RequestValidator $validator, Holiday $holiday): JsonResponse
    {
        try {
            $object = $validator->validate((string)$request->getContent(), Holiday::class, $holiday);

            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->respondWithResource($object);
        } catch (BadRequestHttpException $e) {
            return $this->respondInvalidRequest($e->getMessage());
        }
    }

    /**
     * @Route("/{id}", name="holiday_delete", methods={"DELETE"}, requirements={"id":"\d+"})
     */
    public function deleteAction(Holiday $holiday): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($holiday);
        $em->flush();

        return $this->respondDeleted();
    }
}
