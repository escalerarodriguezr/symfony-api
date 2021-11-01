<?php

namespace App\Listener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTAuthenticatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTAuthenticatedListener
{

    public function __construct(private RequestStack $requestStack)
    {

    }

    public function onJWTAuthenticated(JWTAuthenticatedEvent $event)
    {
        $userId = $event->getPayload()['userId'];
        $request = $this->requestStack->getCurrentRequest();
        $request->attributes->set('userId', $userId);
    }

}