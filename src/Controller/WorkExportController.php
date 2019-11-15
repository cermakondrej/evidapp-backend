<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\WorkExport;
use App\Entity\VariableWorkExport;
use App\Entity\EmployeeWorkExport;
use App\Service\Export\EmployeeWorkExporter;
use App\Service\Export\VariableWorkExporter;
use App\Validation\RequestValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("exports")
 */
class WorkExportController extends BaseController
{

    /**
     * @Route("/variable", name="variable_export", methods={"POST"})
     */
    public function variableAction(Request $request, RequestValidator $validator, VariableWorkExporter $exporter): JsonResponse
    {
        try{
            $object = $validator->validate($request->getContent(), VariableWorkExport::class);
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            $export = $exporter->createExport($object);

            return $this->respondCreated($export);

        } catch(BadRequestHttpException $e){
            return $this->respondInvalidRequest($e->getMessage());
        }
    }

    public function regularAction(Request $request, RequestValidator $validator, EmployeeWorkExporter $exporter): JsonResponse
    {
        try{
            $object = $validator->validate($request->getContent(), EmployeeWorkExport::class);

            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            $export = $exporter->createExport($object);

            return $this->respondCreated($export);

        } catch(BadRequestHttpException $e){
            return $this->respondInvalidRequest($e->getMessage());
        }

    }

    /**
     * @Route("/{id}", name="export_detail", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function detailAction(WorkExport $export): JsonResponse
    {
        return $this->respondWithResource($export);

    }
}