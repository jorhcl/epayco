<?php

namespace Tests\Unit\src\Application\UsesCases;

use PHPUnit\Framework\TestCase;
use Src\Application\UseCases\LoadWalletUseCase;
use Src\Domain\Wallet\Wallet;

use Exception;
use Src\Infrastructure\Persistence\Doctrine\TransactionRepository;
use Src\Infrastructure\Persistence\Doctrine\WalletRepository;

class LoadWalletUseCaseTest extends TestCase
{
    /**
     * Prueba para verificar que se carga la wallet correctamente.
     *
     */
    public function test_handle_loads_wallet_for_client()
    {
        $document = '123456789';
        $celPhone = '9981447710';
        $amount = 100.0;

        $walletMock = $this->createMock(Wallet::class);
        $walletRepositoryMock = $this->createMock(WalletRepository::class);


        $walletRepositoryMock->expects($this->once())
            ->method('findByDocumentAndCelPhone')
            ->with($document, $celPhone)
            ->willReturn($walletMock);

        $walletMock->expects($this->once())
            ->method('load')
            ->with($amount);



        $walletRepositoryMock->expects($this->once())
            ->method('save')
            ->with($walletMock);

        $transactionRepositoryMock = $this->createMock(TransactionRepository::class);

        $useCase = new LoadWalletUseCase($walletRepositoryMock, $transactionRepositoryMock);

        $useCase->handle($document, $celPhone, $amount);
    }
}
