<?php

namespace Src\Domain\Wallet;

interface TransactionRepositoryInterface
{
    /**
     * Guarda una transacción.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function save(Transaction $transaction): void;

    /**
     * Busca transacción por ID.
     *
     * @param int $id
     * @return Transaction|null
     */
    public function findById(int $id): ?Transaction;

    /**
     * Busca transacciones por wallet.
     *
     * @param Wallet $wallet
     * @return Transaction[]
     */
    public function findByWallet(Wallet $wallet): array;

    /**
     * Busca transacción por token y session_id.
     *
     * @param string $token
     * @param string $sessionId
     * @return Transaction|null
     */
    public function findByTokenAndSessionId(string $token, string $sessionId): ?Transaction;
}
