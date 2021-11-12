<?php
declare(strict_types=1);

namespace App\Messenger\Handler;

use App\Messenger\Message\User\RegisterUserMessage;
use App\Service\Shared\Mailer\AppRoute;
use App\Service\Shared\Mailer\MailerService;
use App\Templating\TwigTemplate;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RegisterUserMessageHandler implements MessageHandlerInterface
{

    public function __construct(
        private MailerService $mailerService,
        private string $appHost
    )
    {

    }

    /**
     * @throws \Twig\Error\SyntaxError
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
     */
    public function __invoke(RegisterUserMessage $message): void
    {
        $payload = [
            'name' => $message->getFullname(),
            'url' => sprintf('%s%s/%s', $this->appHost, AppRoute::ACTIVATE_ACCOUNT, $message->getActivationCode())
        ];

        $this->mailerService->send($message->getEmail(),TwigTemplate::USER_REGISTER, $payload);
    }

}