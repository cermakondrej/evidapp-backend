<?php


namespace App\Component\Common\Domain\Exception;

class InvalidUUIDException extends \InvalidArgumentException
{

    public function __construct()
    {
        parent::__construct("aggregator_root.exception.invalid_uuid", 400);
    }
}