<?php
declare(strict_types=1);

namespace App\Validation;

use Exception;
use App\Util\Violation;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestValidator implements RequestValidatorInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var Violation
     */
    private $violator;

    /**
     * RequestValidator constructor.
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param Violation $violator
     */
    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        Violation $violator
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->violator = $violator;
    }


    /**
     * @param string $data
     * @param string $model
     * @return object
     * @throws BadRequestHttpException
     */
    public function validate(string $data, string $model): object
    {
        if (!$data) {
            throw new BadRequestHttpException('Empty body.');
        }

        try {
            $object = $this->serializer->deserialize($data, $model, 'json');
        } catch (Exception $e) {
            throw new BadRequestHttpException('Invalid body.');
        }

        $errors = $this->validator->validate($object);

        if ($errors->count()) {
            throw new BadRequestHttpException(json_encode($this->violator->build($errors)));
        }
        return $object;
    }
}