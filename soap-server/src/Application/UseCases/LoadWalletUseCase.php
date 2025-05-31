<?php

namespace Src\Application\UseCases;

use Src\Domain\Wallet\Wallet;
use Exception;
use Src\Domain\Wallet\Transaction;
use Src\Infrastructure\Persistence\Doctrine\TransactionRepository;
use Src\Infrastructure\Persistence\Doctrine\WalletRepository;

class LoadWalletUseCase
{
    private WalletRepository $walletRepository;
    private TransactionRepository $transactionRepository;


    public function __construct(WalletRepository $walletRepository, TransactionRepository $transactionRepository)
    {

        $this->walletRepository = $walletRepository;
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * Carga una wallet por documento y telefono movil.
     *
     * @param string $document
     * @param string $celPhone
     * @return Wallet|null
     */
    public function handle(string $document, string $celPhone, float $amount): ?Wallet
    {
        $wallet = $this->walletRepository->findByDocumentAndCelPhone($document, $celPhone);

        if (!$wallet) {
            return null;
        }
        $wallet->load($amount);
        $this->walletRepository->save($wallet);

        $transaction = new Transaction(
            wallet: $wallet,
            amount: $amount,
            description: 'Carga de wallet',
        );

        $transaction->setStatus('Completed');
        $this->transactionRepository->save($transaction);

        return $wallet;
    }
}
