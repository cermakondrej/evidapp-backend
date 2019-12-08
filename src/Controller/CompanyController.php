<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use App\Validation\RequestValidator;
use App\Validation\RequestValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("companies")
 */
class CompanyController extends BaseController
{
    /**
     * @Route("/", name="company_list", methods={"GET"})
     */
    public function listAction(CompanyRepository $repository, Request $request): JsonResponse
    {
        // TODO EXTRACT THIS INTO SOME KIND OF MIDDLEWARE
        $limit = (int)$request->query->get('limit') ?? 25;
        $page = (int)$request->query->get('page') ?? 1;

        return $this->respond($repository->findAll());
    }

    /**
     * @Route("/{id}", name="company_detail", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function detailAction(Company $company): JsonResponse
    {
        return $this->respondWithResource($company);
    }

    /**
     * @Route("/", name="company_new", methods={"POST"})
     */
    public function newAction(Request $request, RequestValidator $validator): JsonResponse
    {

        try {
            $object = $validator->validate((string)$request->getContent(), Company::class);

            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->respondCreated($object);
        } catch (BadRequestHttpException $e) {
            return $this->respondInvalidRequest($e->getMessage());
        }
    }


    /**
     * @Route("/{id}", name="company_edit", methods={"PATCH", "PUT"}, requirements={"id":"\d+"})
     */
    public function editAction(Request $request, RequestValidator $validator, Company $company): JsonResponse
    {
        try {
            $object = $validator->validate((string) $request->getContent(), Company::class, $company);


            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->respondWithResource($object);
        } catch (BadRequestHttpException $e) {
            return $this->respondInvalidRequest($e->getMessage());
        }
    }

    /**
     * @Route("/{id}", name="company_delete", methods={"DELETE"}, requirements={"id":"\d+"})
     */
    public function deleteAction(Company $company): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($company);
        $em->flush();

        return $this->respondDeleted();
    }
}
