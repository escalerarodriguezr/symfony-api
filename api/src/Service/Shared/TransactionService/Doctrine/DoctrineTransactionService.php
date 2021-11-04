<?php
declare(strict_types=1);
namespace App\Service\Shared\TransactionService\Doctrine;

use App\Service\Shared\TransactionService\TransactionServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineTransactionService implements TransactionServiceInterface
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function start(): void
    {
        $this->entityManager->getConnection()->beginTransaction();
    }
    public function commit(): void
    {
        $this->entityManager->flush();
        if ($this->entityManager->getConnection()->isTransactionActive()) {
            $this->entityManager->getConnection()->commit();
        }
    }
    public function rollback(): void
    {
        if ($this->entityManager->getConnection()->isTransactionActive()) {
            $this->entityManager->getConnection()->rollBack();
        }
        $this->entityManager->clear();
    }

}