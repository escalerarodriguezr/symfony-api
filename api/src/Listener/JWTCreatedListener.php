<?php
declare(strict_types=1);

namespace App\Listener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;


class JWTCreatedListener
{
    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $user = $event->getUser();
        $payload = $event->getData();
        unset($payload['roles']);
        $payload['userId'] = $user->getId();
        $event->setData($payload);
    }

}