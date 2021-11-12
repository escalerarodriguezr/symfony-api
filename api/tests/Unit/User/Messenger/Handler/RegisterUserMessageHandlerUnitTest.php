<?php
declare(strict_types=1);

namespace App\Tests\Unit\User\Messenger\Handler;

use App\Messenger\Handler\RegisterUserMessageHandler;
use App\Messenger\Message\User\RegisterUserMessage;
use App\Service\Shared\Mailer\MailerService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RegisterUserMessageHandlerUnitTest extends TestCase
{
    private MockObject|MailerService $mailerService;
    private string $appHost;
    private RegisterUserMessageHandler $handler;

    public function setUp(): void
    {
        parent::setUp();
        $this->mailerService = $this->getMockBuilder(MailerService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->appHost = 'fake-host';
        $this->handler = new RegisterUserMessageHandler($this->mailerService, $this->appHost);
    }

    public function testRegisterUserMessageHandler(): void
    {
        $message = $this->getMockBuilder(RegisterUserMessage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $message->method('getEmail')->willReturn('email@fake.com');
        $message->method('getFullName')->willReturn('fake-name');
        $message->method('getActivationCode')->willReturn('fake-code');

        $message->expects($this->exactly(1))
            ->method('getFullName')
            ->willReturn('fake-name');

        $message->expects($this->exactly(1))
            ->method('getEmail')
            ->willReturn('email@fake.com');

        $message->expects($this->exactly(1))
            ->method('getActivationCode')
            ->willReturn('fake-code');

        $this->mailerService->expects($this->exactly(1))
            ->method('send')
            ->with($this->isType('string'),$this->isType('string'), $this->isType('array'));

        $this->handler->__invoke($message);

    }

}