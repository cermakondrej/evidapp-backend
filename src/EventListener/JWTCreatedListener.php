<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener
{

    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $payload = $event->getData();

        /** @var User */
        $user = $event->getUser();
        $payload['full_name'] = $user->getFullName();
        $payload['id'] = $user->getId();
        $event->setData($payload);
    }
}