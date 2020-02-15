<?php


namespace EvidApp\User\Domain\ValueObject;


use EvidApp\Common\Domain\ValueObject\AggregateRootId;

class UserId extends AggregateRootId
{
    /** @var  string */
    protected $uuid;
}