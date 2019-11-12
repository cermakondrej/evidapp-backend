<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Job;
use App\Repository\JobRepository;
use App\Validation\RequestValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("jobs")
 */
class JobController extends BaseController
{
    /**
     * @Route("/", name="job_list", methods={"GET"})
     */
    public function listAction(JobRepository $repository, Request $request): JsonResponse
    {
        // TODO EXTRACT THIS INTO SOME KIND OF MIDDLEWARE
        $limit = (int) $request->query->get('limit') ?? 25;
        $page = (int) $request->query->get('page') ?? 1;

        return $this->respondWithPagination($repository->findAllPaginated($limit, $page));

    }

    /**
     * @Route("/{id}", name="job_detail", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function detailAction(Job $job): JsonResponse
    {
        return $this->respondWithResource($job);

    }

    /**
     * @Route("/", name="job_new", methods={"POST"})
     */
    public function newAction(Request $request, RequestValidator $validator): JsonResponse
    {

        try{
            $object = $validator->validate($request->getContent(), Job::class);

            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->respondCreated($object);

        } catch(BadRequestHttpException $e){
            return $this->respondInvalidRequest($e->getMessage());
        }

    }


    /**
     * @Route("/{id}", name="job_edit", methods={"PATCH", "PUT"}, requirements={"id":"\d+"})
     */
    public function editAction(Request $request, RequestValidator $validator, Job $job): JsonResponse
    {
        try {
            $object = $validator->validate($request->getContent(), Job::class, $job);


            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->respondWithResource($object);

        } catch(BadRequestHttpException $e){
            return $this->respondInvalidRequest($e->getMessage());
        }

    }

    /**
     * @Route("/{id}", name="job_delete", methods={"DELETE"}, requirements={"id":"\d+"})
     */
    public function deleteAction(Job $job): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($job);
        $em->flush();

        return $this->respondDeleted();
    }

}
