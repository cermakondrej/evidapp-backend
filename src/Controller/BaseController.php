<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\SerializerInterface;
use App\Pagination\Paginator;

class BaseController extends AbstractController
{

    /**
     * @var int
     */
    private $statusCode = JsonResponse::HTTP_OK;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * BaseController constructor.
     * @param $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }


    /**
     * Gets the value of statusCode.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Sets the value of statusCode.
     *
     * @param int $statusCode the status code
     *
     * @return self
     */
    private function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    protected function responseNotFound(string $message = 'Not Found!'): JsonResponse
    {
        return $this->setStatusCode(JsonResponse::HTTP_NOT_FOUND)->respondWithError($message);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    protected function respondInvalidRequest(string $message = 'Invalid Request!'): JsonResponse
    {
        return $this->setStatusCode(JsonResponse::HTTP_BAD_REQUEST)->respondWithError($message);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    protected function respondInternalError(string $message = 'Internal Error'): JsonResponse
    {
        return $this->setStatusCode(JsonResponse::HTTP_INTERNAL_SERVER_ERROR)->respondWithError($message);
    }

    /**
     * @param object $object
     * @return JsonResponse
     */
    protected function respondWithResource(object $object): JsonResponse
    {
        return $this->respond($this->serializer->serialize($object, 'json'), [], true);
    }

    /**
     * @param object $object
     * @return JsonResponse
     */
    protected function respondCreated(object $object): JsonResponse
    {
        return $this->setStatusCode(JsonResponse::HTTP_CREATED)->respondWithResource($object);

    }

    /**
     * @return JsonResponse
     */
    protected function respondDeleted(): JsonResponse
    {
        return $this->setStatusCode(JsonResponse::HTTP_NO_CONTENT)->respond();

    }

    /**
     * @param Paginator $paginator
     * @return JsonResponse
     */
    protected function respondWithPagination(Paginator $paginator): JsonResponse
    {
        $data = array_merge(['items' => $paginator->getResults()], [
            'paginator' => [
                'total_count' => $paginator->getNumResults(),
                'total_pages' => $paginator->getLastPage(),
                'current_page' => $paginator->getCurrentPage(),
                'limit' => $paginator->getPageSize()
            ]
        ]);

        return $this->respond($this->serializer->serialize($data, 'json'), [], true);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    protected function respondWithError(string $message): JsonResponse
    {
        return $this->respond([
            'error' => [
                'message' => $message,
                'status_code' => $this->getStatusCode()
            ]
        ]);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    protected function respondWithMessage(string $message = 'All is well.'): JsonResponse
    {
        return $this->respond([
            'response' => [
                'message' => $message,
                'status_code' => $this->getStatusCode()
            ]
        ]);
    }


    /**
     * @param array|string $data
     * @param array $headers
     * @param bool $json
     * @return JsonResponse
     */
    private function respond($data = [], array $headers = [], bool $json = false): JsonResponse
    {

        return new JsonResponse($data, $this->getStatusCode(), $headers, $json);
    }

}