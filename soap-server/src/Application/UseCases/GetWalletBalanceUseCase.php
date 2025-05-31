<?php

namespace Src\Application\UseCases;

use Src\Infrastructure\Persistence\Doctrine\WalletRepository;

class GetWalletBalanceUseCase
{
    private WalletRepository $walletRepository;

    public function __construct(WalletRepository $walletRepository)
    {
        $this->walletRepository = $walletRepository;
    }

    /**
     * Obtiene el saldo de la wallet del cliente.
     *
     * @param string $document
     * @param string $celPhone
     * @return array
     */
    public function handle(string $document, string $celPhone): ?float
    {
        $wallet =  $this->walletRepository->findByDocumentAndCelPhone($document, $celPhone);
        if (!$wallet) {
            return null;
        }

        return $wallet->getBalance();
    }
}
