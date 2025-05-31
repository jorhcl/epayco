<?php


namespace Src\Application\UseCases;

use Src\Domain\Wallet\Transaction;
use Src\Infrastructure\Persistence\Doctrine\TransactionRepository;
use Src\Infrastructure\Persistence\Doctrine\WalletRepository;

class GetWalletTransactionsUseCase
{
    private WalletRepository $walletRepository;
    private TransactionRepository $transactionRepository;

    public function __construct(WalletRepository $walletRepository, TransactionRepository $transactionRepository)
    {
        $this->walletRepository = $walletRepository;
        $this->transactionRepository = $transactionRepository;
    }


    /**
     * Obtiene las transacciones de la wallet del cliente.
     *
     * @param string $document
     * @param string $celPhone
     * @return array|null
     */
    public function handle(string $document, string $celPhone): array
    {
        $wallet = $this->walletRepository->findByDocumentAndCelPhone($document, $celPhone);
        if (!$wallet) {
            return [
                'success' => 0,
                'error_code' => 'WALLET_NOT_FOUND',
                'message' => 'Wallet no encontrada para el documento y celular proporcionados.',
            ];
        }

        $transactions = $this->transactionRepository->findByWallet($wallet);
        if (empty($transactions)) {
            return [
                'success' => 0,
                'error_code' => 'NO_TRANSACTIONS_FOUND',
                'message' => 'No se encontraron transacciones para la wallet proporcionada.',
            ];
        }

        return array_map(function (Transaction $transaction) {
            return [

                'amount' => $transaction->getAmount(),
                'description' => $transaction->getDescription(),
                'status' => $transaction->getStatus(),
                'created_at' => $transaction->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }, $transactions);
    }
}
