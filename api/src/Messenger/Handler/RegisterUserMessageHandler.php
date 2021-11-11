<?php
declare(strict_types=1);

namespace App\Messenger\Handler;

use App\Messenger\Message\User\RegisterUserMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RegisterUserMessageHandler implements MessageHandlerInterface
{

    public function __construct(private LoggerInterface $logger)
    {

    }
    public function __invoke(RegisterUserMessage $message): void
    {
        $this->logger->info(\sprintf('Enviar a %s a su email: %s el code: %s', $message->getFullname(),$message->getEmail(), $message->getActivationCode()));
    }

}