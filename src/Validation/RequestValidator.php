<?php
declare(strict_types=1);

namespace App\Validation;

use Exception;
use App\Util\Violation;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestValidator
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

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        Violation $violator
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->violator = $violator;
    }

    public function validate(string $data, string $model, object $objectToPopulate = null): object
    {
        if (!$data) {
            throw new BadRequestHttpException('Empty body.');
        }

        try {
            $object = $this->serializer->deserialize($data, $model, 'json', ['object_to_populate' => $objectToPopulate]);
        } catch (Exception $e) {
            throw new BadRequestHttpException('Invalid body.');
        }

//        $object = $this->serializer->deserialize($data, $model, 'json', ['object_to_populate' => $objectToPopulate]);

        $errors = $this->validator->validate($object);

        if ($errors->count()) {
            throw new BadRequestHttpException(json_encode($this->violator->build($errors)));
        }
        return $object;
    }
}