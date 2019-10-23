<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Company;
use App\Pagination\Paginator;
use App\Repository\CompanyRepository;
use App\Validation\RequestValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Company controller.
 *
 * @Route("companies")
 */
class CompanyController extends BaseController
{
    /**
     * Lists all company entities.
     *
     * @Route("/", name="company_list", methods={"GET"})
     * @param CompanyRepository $repository
     * @return JsonResponse
     */
    public function listAction(CompanyRepository $repository, Request $request): JsonResponse
    {
        // TODO EXTRACT THIS INTO SOME KIND OF MIDDLEWARE
        $limit = (int) $request->query->get('limit') ?? 25;
        $page = (int) $request->query->get('page') ?? 1;

        return $this->respondWithPagination($repository->findAllPaginated($limit, $page));

    }

    /**
     * Returns detail of company entity.
     *
     * @Route("/{id}", name="company_detail", methods={"GET"}, requirements={"id":"\d+"})
     * @param Company $company
     * @return JsonResponse
     */
    public function detailAction(Company $company): JsonResponse
    {
        return $this->respondWithResource($company);

    }



    /**
     * Creates a new company entity.
     *
     * @Route("/", name="company_new", methods={"POST"})
     * @param Request $request
     * @param RequestValidatorInterface $validator
     * @return JsonResponse
     */
    public function newAction(Request $request, RequestValidatorInterface $validator): JsonResponse
    {

        try{
            $object = $validator->validate($request->getContent(), Company::class);

            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->respondCreated($object);

        } catch(BadRequestHttpException $e){
            return $this->respondInvalidRequest($e->getMessage());
        }

    }


    /**
     * Edits an existing Company entity.
     *
     * @Route("/{id}", name="compamy_edit", methods={"PATCH", "PUT"}, requirements={"id":"\d+"})
     * @param Request $request
     * @param RequestValidatorInterface $validator
     * @param Company $company
     * @return JsonResponse
     */
    public function editAction(Request $request, RequestValidatorInterface $validator, Company $company): JsonResponse
    {
        try {
            $object = $validator->validate($request->getContent(), Company::class);

            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->respondWithResource($object);

        } catch(BadRequestHttpException $e){
            return $this->respondInvalidRequest($e->getMessage());
        }

    }

    /**
     * Deletes an existing company entity.
     *
     * @Route("/{id}", name="company_delete", methods={"DELETE"})
     * @param Company $company
     * @return JsonResponse
     */
    public function deleteAction(Company $company): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($company);
        $em->flush();

        return $this->respondDeleted();
    }

}
