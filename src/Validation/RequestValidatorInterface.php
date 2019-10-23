<?php
declare(strict_types=1);

namespace App\Validation;

interface RequestValidatorInterface
{

    /**
     * @param string $data
     * @param string $model
     * @return object
     */
    public function validate(string $data, string $model): object;
}