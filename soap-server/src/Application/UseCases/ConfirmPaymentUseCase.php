<?php

namespace Src\Application\UseCases;

use Src\Domain\Wallet\WalletService;
use Src\Infrastructure\Persistence\Doctrine\TransactionRepository;
use Src\Infrastructure\Persistence\Doctrine\WalletRepository;

class ConfirmPaymentUseCase
{

    private TransactionRepository $transactionRepository;
    private WalletRepository $walletRepository;
    private WalletService $walletService;


    public function __construct(
        TransactionRepository $transactionRepository,
        WalletRepository $walletRepository,
        WalletService $walletService
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->walletRepository = $walletRepository;
        $this->walletService = $walletService;
    }

    /**
     * Confirma el pago realizado con la wallet del cliente.
     *
     * @param string $token
     * @param string $sessionId
     * @return array
     */
    public function handle(string $token, string $sessionId): array
    {
        $transaction = $this->transactionRepository->findByTokenAndSessionId($token, $sessionId);

        if (!$transaction) {
            return [
                'success' => 0,
                'error_code' => 'TRANSACTION_NOT_FOUND',
                'message' => 'Transacción no encontrada para el token y session_id proporcionados.',
            ];
        }

        if ($transaction->getStatus() !== 'Pending') {
            return [
                'success' => 0,
                'error_code' => 'TRANSACTION_ALREADY_CONFIRMED',
                'message' => 'La transacción ya ha sido confirmada o no está pendiente.',
            ];
        }

        $wallet = $transaction->getWallet();
        if (!$this->walletService->hasSufficientBalance($wallet, $transaction->getAmount())) {
            return [
                'success' => 0,
                'error_code' => 'INSUFFICIENT_BALANCE',
                'message' => 'Saldo insuficiente en la wallet.',
            ];
        }
        $wallet->withdraw($transaction->getAmount());
        $transaction->setStatus('Confirmed');
        $this->transactionRepository->save($transaction);
        $this->walletRepository->save($wallet);
        return [
            'success' => 1,
            'message' => 'Pago confirmado exitosamente.',
            'wallet_balance' => $wallet->getBalance(),
        ];
    }
}
