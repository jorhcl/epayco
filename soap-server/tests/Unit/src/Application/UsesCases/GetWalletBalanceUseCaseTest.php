<?php

namespace Tests\Unit\src\Application\UsesCases;

use PHPUnit\Framework\TestCase;
use Src\Application\UseCases\GetWalletBalanceUseCase;
use Src\Domain\Wallet\Wallet;
use Src\Infrastructure\Persistence\Doctrine\WalletRepository;

class GetWalletBalanceUseCaseTest extends TestCase
{
    /**
     *  Prueba para verificar que se obtiene el balance de la wallet correctamente.
     */

    public function test_handle_returns_balance_when_wallet_exists()
    {
        $document = '123456789';
        $celPhone = '9981447710';
        $expectedBalance = 150.0;

        $walletMock = $this->createMock(Wallet::class);
        $walletMock->method('getBalance')->willReturn($expectedBalance);

        $walletRepositoryMock = $this->createMock(WalletRepository::class);
        $walletRepositoryMock->method('findByDocumentAndCelPhone')
            ->with($document, $celPhone)
            ->willReturn($walletMock);

        $useCase = new GetWalletBalanceUseCase($walletRepositoryMock);

        $balance = $useCase->handle($document, $celPhone);

        $this->assertEquals($expectedBalance, $balance);
    }

    /**
     * Prueba para verificar que se retorna null cuando no se encuentra la wallet.
     */
    public function test_handle_returns_null_when_wallet_not_found()
    {
        $document = '123456789';
        $celPhone = '9981447710';

        $walletRepositoryMock = $this->createMock(WalletRepository::class);
        $walletRepositoryMock->method('findByDocumentAndCelPhone')
            ->with($document, $celPhone)
            ->willReturn(null);

        $useCase = new GetWalletBalanceUseCase($walletRepositoryMock);

        $balance = $useCase->handle($document, $celPhone);

        $this->assertNull($balance);
    }
}
