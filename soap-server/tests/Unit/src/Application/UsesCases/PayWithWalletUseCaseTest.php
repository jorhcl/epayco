<?php

namespace Tests\Unit\src\Application\UsesCases;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use ReflectionClass;
use Src\Application\UseCases\PayWithWalletUseCase;
use Src\Domain\Client\Client;
use Src\Domain\Wallet\Transaction;
use Src\Domain\Wallet\Wallet;
use Src\Domain\Wallet\WalletRepositoryInterface;
use Src\Domain\Wallet\WalletService;
use Src\Infrastructure\Persistence\Doctrine\TransactionRepository;
use Src\Infrastructure\Persistence\Doctrine\WalletRepository;

class PayWithWalletUseCaseTest extends TestCase
{
    /**
     * Prueba para verificar que se maneja correctamente el caso cuando no se encuentra la wallet.
     *
     */
    public function test_handle_returns_error_if_wallet_not_found()
    {
        $walletServiceMock = $this->createMock(WalletService::class);
        $walletServiceMock->method('findByDocumentAndCelPhone')->willReturn(null);

        $walletRepositoryMock = $this->createMock(WalletRepository::class);
        $transactionRepositoryMock = $this->createMock(TransactionRepository::class);

        $useCase = new PayWithWalletUseCase($walletServiceMock,  $transactionRepositoryMock);

        $result = $useCase->handle('123456789', '9981447710', 100.0, 'Compra');

        $this->assertEquals(0, $result['success']);
        $this->assertEquals('WALLET_NOT_FOUND', $result['error_code']);
    }


    public function test_handle_returns_error_if_insufficient_balance()
    {
        $walletMock = $this->createMock(Wallet::class);

        $walletServiceMock = $this->createMock(WalletService::class);
        $walletServiceMock->method('findByDocumentAndCelPhone')->willReturn($walletMock);
        $walletServiceMock->method('hasSufficientBalance')->with($walletMock, 100.0)->willReturn(false);

        $walletRepositoryMock = $this->createMock(WalletRepository::class);
        $transactionRepositoryMock = $this->createMock(TransactionRepository::class);

        $useCase = new PayWithWalletUseCase($walletServiceMock,  $transactionRepositoryMock);

        $result = $useCase->handle('123456789', '9981447710', 100.0, 'Compra');

        $this->assertEquals(0, $result['success']);
        $this->assertEquals('INSUFFICIENT_BALANCE', $result['error_code']);
    }


    public function test_handle_successful_payment()
    {
        $walletMock = $this->createMock(Wallet::class);
        $clientMock = $this->createMock(Client::class);
        $clientMock->method('getEmail')->willReturn('jorhcl@hotmail.com');
        $walletMock->method('getClient')->willReturn($clientMock);

        $walletServiceMock = $this->createMock(WalletService::class);
        $walletServiceMock->method('findByDocumentAndCelPhone')->willReturn($walletMock);
        $walletServiceMock->method('hasSufficientBalance')->willReturn(true);
        $walletServiceMock->expects($this->once())->method('sendTokenByEmail');

        $walletRepositoryMock = $this->createMock(WalletRepository::class);

        $transactionMock = $this->createMock(Transaction::class);


        $transactionRepositoryMock = $this->createMock(TransactionRepository::class);
        $transactionRepositoryMock->expects($this->once())->method('save')
            ->with($this->isInstanceOf(Transaction::class))
            ->willReturnCallback(function ($transaction) use ($transactionMock) {

                $reflection = new ReflectionClass($transaction);
                if ($reflection->hasProperty('id')) {
                    $property = $reflection->getProperty('id');
                    $property->setAccessible(true);
                    $property->setValue($transaction, 1);
                }
            });

        $useCase = new PayWithWalletUseCase($walletServiceMock,  $transactionRepositoryMock);

        $result = $useCase->handle('123456789', '9981447710', 100.0, 'Compra');

        $this->assertEquals(1, $result['success']);
        $this->assertEquals('Compra iniciada se ha enviado un token al correo electrónico del cliente.', $result['message']);
    }
}
