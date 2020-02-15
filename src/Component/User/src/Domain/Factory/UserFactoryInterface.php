<?php
declare(strict_types=1);

namespace EvidApp\User\Domain\Factory;


use EvidApp\User\Domain\Entity\User;

interface UserFactoryInterface
{
    public function register(array $data): User;
}