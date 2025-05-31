<?php

namespace Tests\Unit\src\Application\UsesCases;

use PHPUnit\Framework\TestCase;
use Src\Application\UseCases\ConfirmPaymentUseCase;
use Src\Domain\Wallet\Transaction;
use Src\Domain\Wallet\WalletService;
use Src\Infrastructure\Persistence\Doctrine\TransactionRepository;
use Src\Infrastructure\Persistence\Doctrine\WalletRepository;
use Src\Domain\Wallet\Wallet;

class ConfirmPaymentUseCaseTest extends TestCase
{
    /**
     * Prueba para verificar que se maneja correctamente el caso cuando no se encuentra la transacción.
     *
     */
    public function test_handle_transaction_not_found()
    {
        $transactionRepositoryMock = $this->createMock(TransactionRepository::class);
        $walletRepositoryMock = $this->createMock(WalletRepository::class);
        $walletServiceMock = $this->createMock(WalletService::class);

        $transactionRepositoryMock->method('findByTokenAndSessionId')->willReturn(null);

        $useCase = new ConfirmPaymentUseCase($transactionRepositoryMock, $walletRepositoryMock, $walletServiceMock);

        $result = $useCase->handle('token123', 'session456');

        $this->assertEquals(0, $result['success']);
        $this->assertEquals('TRANSACTION_NOT_FOUND', $result['error_code']);
    }

    /**
     * Prueba para verificar que se maneja correctamente el caso cuando la transacción ya ha sido confirmada.
     *
     */
    public function test_handle_transaction_already_confirmed()
    {
        $transactionMock = $this->createMock(Transaction::class);
        $transactionMock->method('getStatus')->willReturn('Confirmed');

        $transactionRepositoryMock = $this->createMock(TransactionRepository::class);
        $transactionRepositoryMock->method('findByTokenAndSessionId')->willReturn($transactionMock);

        $walletRepositoryMock = $this->createMock(WalletRepository::class);
        $walletServiceMock = $this->createMock(WalletService::class);

        $useCase = new ConfirmPaymentUseCase($transactionRepositoryMock, $walletRepositoryMock, $walletServiceMock);

        $result = $useCase->handle('token123', 'session456');

        $this->assertEquals(0, $result['success']);
        $this->assertEquals('TRANSACTION_ALREADY_CONFIRMED', $result['error_code']);
    }

    /**
     * Prueba para verificar que se maneja correctamente el caso cuando no hay saldo suficiente en la wallet.
     *
     */
    public function test_handle_insufficient_balance()
    {
        $walletMock = $this->createMock(Wallet::class);
        $transactionMock = $this->createMock(Transaction::class);
        $transactionMock->method('getStatus')->willReturn('Pending');
        $transactionMock->method('getWallet')->willReturn($walletMock);
        $transactionMock->method('getAmount')->willReturn(100.0);

        $transactionRepositoryMock = $this->createMock(TransactionRepository::class);
        $transactionRepositoryMock->method('findByTokenAndSessionId')->willReturn($transactionMock);

        $walletRepositoryMock = $this->createMock(WalletRepository::class);
        $walletServiceMock = $this->createMock(WalletService::class);
        $walletServiceMock->method('hasSufficientBalance')->willReturn(false);

        $useCase = new ConfirmPaymentUseCase($transactionRepositoryMock, $walletRepositoryMock, $walletServiceMock);

        $result = $useCase->handle('token123', 'session456');

        $this->assertEquals(0, $result['success']);
        $this->assertEquals('INSUFFICIENT_BALANCE', $result['error_code']);
    }

    public function test_handle_successful_confirmation()
    {
        $walletMock = $this->createMock(Wallet::class);
        $walletMock->method('getBalance')->willReturn(900.0);

        $transactionMock = $this->createMock(Transaction::class);
        $transactionMock->method('getStatus')->willReturn('Pending');
        $transactionMock->method('getWallet')->willReturn($walletMock);
        $transactionMock->method('getAmount')->willReturn(100.0);

        $transactionRepositoryMock = $this->createMock(TransactionRepository::class);
        $transactionRepositoryMock->method('findByTokenAndSessionId')->willReturn($transactionMock);
        $transactionRepositoryMock->expects($this->once())->method('save')->with($transactionMock);

        $walletRepositoryMock = $this->createMock(WalletRepository::class);
        $walletRepositoryMock->expects($this->once())->method('save')->with($walletMock);

        $walletServiceMock = $this->createMock(WalletService::class);
        $walletServiceMock->method('hasSufficientBalance')->willReturn(true);

        $transactionMock->expects($this->once())->method('setStatus')->with('Confirmed');
        $walletMock->expects($this->once())->method('withdraw')->with(100.0);

        $useCase = new ConfirmPaymentUseCase($transactionRepositoryMock, $walletRepositoryMock, $walletServiceMock);

        $result = $useCase->handle('token123', 'session456');

        $this->assertEquals(1, $result['success']);
        $this->assertEquals('Pago confirmado exitosamente.', $result['message']);
        $this->assertEquals(900.0, $result['wallet_balance']);
    }
}
