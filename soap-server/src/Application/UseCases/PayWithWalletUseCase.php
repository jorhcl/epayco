<?php

namespace Src\Application\UseCases;

use Src\Domain\Wallet\Transaction;
use Src\Domain\Wallet\WalletService;
use Src\Infrastructure\Persistence\Doctrine\TransactionRepository;
use Src\Infrastructure\Persistence\Doctrine\WalletRepository;

class PayWithWalletUseCase
{
    private WalletService $walletService;

    private TransactionRepository $transactionRepository;

    public function __construct(WalletService $walletService, TransactionRepository $transactionRepository)
    {
        $this->walletService = $walletService;

        $this->transactionRepository = $transactionRepository;
    }

    /**
     * Paga con la wallet del cliente.
     *
     * @param string $document
     * @param string $celPhone
     * @param float $amount
     * @return Wallet|null
     */
    public function handle(string $document, string $celPhone, float $amount, string $description): ?array
    {
        $wallet = $this->walletService->findByDocumentAndCelPhone($document, $celPhone);
        if (!$wallet) {
            return [
                'success' => 0,
                'error_code' => 'WALLET_NOT_FOUND',
                'message' => 'Wallet no encontrada para el documento y celular proporcionados.',
            ];
        }

        if (!$this->walletService->hasSufficientBalance($wallet, $amount)) {
            return [
                'success' => 0,
                'error_code' => 'INSUFFICIENT_BALANCE',
                'message' => 'Saldo insuficiente en la wallet.',
            ];
        }

        $token = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $session_id = bin2hex(random_bytes(16));

        $transaction = new Transaction(
            wallet: $wallet,
            amount: $amount,
            description: $description,
        );

        $transaction->setStatus('Pending');
        $transaction->setToken($token);
        $transaction->setSessionId($session_id);
        $this->transactionRepository->save($transaction);


        $this->walletService->sendTokenByEmail($wallet->getClient()->getEmail(), $token, $session_id);




        return [
            'success' => 1,
            'message' => 'Compra iniciada se ha enviado un token al correo electrónico del cliente.',
            'transaction' => [
                'token' => $token,
                'session_id' => $session_id,
                'amount' => $transaction->getAmount(),
                'description' => $transaction->getDescription(),
            ],
            'wallet' => [
                'balance' => $wallet->getBalance(),
            ],

        ];
    }
}
