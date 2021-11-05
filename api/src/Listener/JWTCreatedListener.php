<?php
declare(strict_types=1);

namespace App\Listener;

use App\Entity\User;
use App\Exception\User\UserAccountNotActiveException;
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

        if(!$user->getActive() || !$user->getConfirmedEmail()){
            throw UserAccountNotActiveException::fromLoginService($user->getEmail());
        }

        $payload = $event->getData();
        unset($payload['roles']);
        $payload['userId'] = $user->getId();
        $event->setData($payload);
    }

}