<?php
declare(strict_types=1);

namespace App\Tests\Functional\Service;

use App\Service\Shared\TransactionService\TransactionServiceInterface;

class MockDoctrineTransactionService implements TransactionServiceInterface
{

    public function start(): void
    {

    }
    public function commit(): void
    {

    }
    public function rollback(): void
    {

    }

}