<?php

namespace Src\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Src\Domain\Wallet\Transaction;
use Src\Domain\Wallet\TransactionRepositoryInterface;
use Src\Domain\Wallet\Wallet;

class TransactionRepository implements TransactionRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(\Src\Domain\Wallet\Transaction $transaction): void
    {
        $this->entityManager->persist($transaction);
        $this->entityManager->flush();
    }

    public function findById(int $id): ?Transaction
    {
        return $this->entityManager->getRepository(Transaction::class)->find($id);
    }

    public function findByWallet(Wallet $wallet): array
    {
        return $this->entityManager->getRepository(Transaction::class)->findBy(['wallet' => $wallet], ['createdAt' => 'DESC']);
    }

    public function findByTokenAndSessionId(string $token, string $sessionId): ?Transaction
    {
        return $this->entityManager->getRepository(Transaction::class)->findOneBy([
            'token' => $token,
            'session_id' => $sessionId,
        ]);
    }
}
