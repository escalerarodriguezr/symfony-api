<?php

namespace App\Service\Shared\TransactionService;

interface TransactionServiceInterface
{
    public function start(): void;
    public function commit(): void;
    public function rollback(): void;

}