<?php
declare(strict_types=1);

namespace App\EventSubscriber;

use App\Service\Shared\TransactionService\TransactionServiceInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

final class RequestTransactionSubscriber implements EventSubscriberInterface
{

    private TransactionServiceInterface $transactionService;

    public function __construct(TransactionServiceInterface $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => ['startTransaction', 200],
            KernelEvents::RESPONSE => ['commitTransaction', 200],
            // In the case that both the Exception and Response events are triggered, we want to make sure the
            // transaction is rolled back before trying to commit it.
            KernelEvents::EXCEPTION => ['rollbackTransaction', 201],
        ];
    }
    public function startTransaction(): void
    {
        $this->transactionService->start();
    }
    public function commitTransaction(): void
    {
        $this->transactionService->commit();
    }
    public function rollbackTransaction(): void
    {
        $this->transactionService->rollback();
    }

}