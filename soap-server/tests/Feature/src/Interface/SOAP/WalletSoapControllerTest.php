<?php

namespace Tests\Feature\src\Interface\SOAP;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Src\Application\UseCases\LoadWalletUseCase;
use Src\Domain\Wallet\Wallet;
use Src\Interface\SOAP\WalletSoapController;
use Exception;
use Src\Application\UseCases\ConfirmPaymentUseCase;
use Src\Application\UseCases\GetWalletBalanceUseCase;
use Src\Application\UseCases\GetWalletTransactionsUseCase;
use Src\Application\UseCases\PayWithWalletUseCase;
use Tests\TestCase;

class WalletSoapControllerTest extends TestCase
{
    public function test_load_wallet_success()
    {
        $document = '123456789';
        $celPhone = '9981447710';
        $amount = 100.0;

        $walletMock = $this->createMock(Wallet::class);
        $walletMock->method('getBalance')->willReturn(200.0);

        $useCaseMock = $this->createMock(LoadWalletUseCase::class);

        $payWithWalletUseCaseMock = $this->createMock(PayWithWalletUseCase::class);

        $confirmPaymentUseCaseMock = $this->createMock(ConfirmPaymentUseCase::class);

        $getWalletBalanceUseCaseMock = $this->createMock(GetWalletBalanceUseCase::class);
        $getWalletTransactionsUseCaseMock = $this->createMock(GetWalletTransactionsUseCase::class);

        $useCaseMock->method('handle')
            ->with($document, $celPhone, $amount)
            ->willReturn($walletMock);

        $controller = new WalletSoapController($useCaseMock, $payWithWalletUseCaseMock, $confirmPaymentUseCaseMock, $getWalletBalanceUseCaseMock, $getWalletTransactionsUseCaseMock);

        $response = $controller->loadWallet($document, $celPhone, $amount);

        $this->assertTrue($response['success']);
        $this->assertEquals('Saldo agregado correctamente.', $response['message']);
        $this->assertEquals(200.0, $response['data']['balance']);
    }

    public function test_load_wallet_error()
    {
        $document = '123456789';
        $celPhone = '9981447710';
        $amount = 100.0;

        $useCaseMock = $this->createMock(LoadWalletUseCase::class);
        $payWithWalletUseCaseMock = $this->createMock(PayWithWalletUseCase::class);
        $confirmPaymentUseCaseMock = $this->createMock(ConfirmPaymentUseCase::class);
        $getWalletBalanceUseCaseMock = $this->createMock(GetWalletBalanceUseCase::class);
        $getWalletTransactionsUseCaseMock = $this->createMock(GetWalletTransactionsUseCase::class);

        $useCaseMock->method('handle')
            ->willThrowException(new Exception('No se encontró la wallet', 404));

        $controller = new WalletSoapController($useCaseMock, $payWithWalletUseCaseMock, $confirmPaymentUseCaseMock, $getWalletBalanceUseCaseMock, $getWalletTransactionsUseCaseMock);

        $response = $controller->loadWallet($document, $celPhone, $amount);

        $this->assertFalse($response['success']);
        $this->assertStringContainsString('Error al cargar la wallet', $response['message']);
        $this->assertEquals(500, $response['code']);
    }

    public function test_load_wallet_not_found()
    {
        $document = '123456789';
        $celPhone = '9981447710';
        $amount = 100.0;

        $useCaseMock = $this->createMock(LoadWalletUseCase::class);

        $payWithWalletUseCaseMock = $this->createMock(PayWithWalletUseCase::class);
        $confirmPaymentUseCaseMock = $this->createMock(ConfirmPaymentUseCase::class);
        $getWalletBalanceUseCaseMock = $this->createMock(GetWalletBalanceUseCase::class);
        $getWalletTransactionsUseCaseMock = $this->createMock(GetWalletTransactionsUseCase::class);

        $useCaseMock->method('handle')
            ->with($document, $celPhone, $amount)
            ->willReturn(null);

        $controller = new WalletSoapController($useCaseMock, $payWithWalletUseCaseMock, $confirmPaymentUseCaseMock, $getWalletBalanceUseCaseMock, $getWalletTransactionsUseCaseMock);

        $response = $controller->loadWallet($document, $celPhone, $amount);

        $this->assertFalse($response['success']);
        $this->assertEquals('No se encontró la wallet para el documento y teléfono proporcionados.', $response['message']);
        $this->assertEquals(404, $response['code']);
    }


    public function test_pay_with_wallet_success()
    {
        $document = '123456789';
        $celPhone = '9981447710';
        $amount = 100.0;
        $description = 'Compra de prueba';
        $token = '123456';

        $walletMock = $this->createMock(Wallet::class);
        $walletMock->method('getBalance')->willReturn(200.0);

        $useCaseMock = $this->createMock(PayWithWalletUseCase::class);
        $useCaseMock->method('handle')
            ->with($document, $celPhone, $amount, $description)
            ->willReturn([
                'success' => 1,
                'message' => 'Compra iniciada se ha enviado un token al correo electrónico del cliente.',
                'transaction' => [
                    'token' => $token,
                    'amount' => $amount,
                    'description' => $description,
                ],
                'wallet' => [
                    'balance' => 200.0,
                ],
            ]);

        $loadWalletUseCaseMock = $this->createMock(LoadWalletUseCase::class);
        $confirmPaymentUseCaseMock = $this->createMock(ConfirmPaymentUseCase::class);
        $getWalletBalanceUseCaseMock = $this->createMock(GetWalletBalanceUseCase::class);
        $getWalletTransactionsUseCaseMock = $this->createMock(GetWalletTransactionsUseCase::class);

        $controller = new WalletSoapController($loadWalletUseCaseMock, $useCaseMock, $confirmPaymentUseCaseMock, $getWalletBalanceUseCaseMock, $getWalletTransactionsUseCaseMock);

        $response = $controller->payWithWallet($document, $celPhone, $amount, $description);


        $this->assertTrue($response['success']);
        $this->assertEquals('Compra iniciada ', $response['message']);
        $this->assertEquals(200.0, $response['data']['balance']);
    }


    public function test_confirm_payment_success()
    {
        $token = '123456';
        $sessionId = 'abcdef';

        $confirmPaymentUseCaseMock = $this->createMock(ConfirmPaymentUseCase::class);
        $confirmPaymentUseCaseMock->method('handle')
            ->with($token, $sessionId)
            ->willReturn([
                'success' => 1,
                'message' => 'Pago confirmado exitosamente.',
                'wallet_balance' => 100.0,
            ]);

        $loadWalletUseCaseMock = $this->createMock(LoadWalletUseCase::class);
        $payWithWalletUseCaseMock = $this->createMock(PayWithWalletUseCase::class);
        $getWalletBalanceUseCaseMock = $this->createMock(GetWalletBalanceUseCase::class);
        $getWalletTransactionsUseCaseMock = $this->createMock(GetWalletTransactionsUseCase::class);

        $controller = new WalletSoapController($loadWalletUseCaseMock, $payWithWalletUseCaseMock, $confirmPaymentUseCaseMock, $getWalletBalanceUseCaseMock, $getWalletTransactionsUseCaseMock);

        $response = $controller->confirmPayment($token, $sessionId);

        $this->assertTrue($response['success']);
        $this->assertEquals('Pago confirmado exitosamente.', $response['message']);
        $this->assertEquals(100.0, $response['data']['wallet_balance']);
    }


    public function test_get_wallet_balance_success()
    {
        $document = '123456789';
        $celPhone = '9981447710';
        $expectedBalance = 150.0;

        $walletMock = $this->createMock(Wallet::class);
        $walletMock->method('getBalance')->willReturn($expectedBalance);

        $getWalletBalanceUseCaseMock = $this->createMock(GetWalletBalanceUseCase::class);
        $getWalletBalanceUseCaseMock->method('handle')
            ->with($document, $celPhone)
            ->willReturn($expectedBalance);

        $loadWalletUseCaseMock = $this->createMock(LoadWalletUseCase::class);
        $payWithWalletUseCaseMock = $this->createMock(PayWithWalletUseCase::class);
        $confirmPaymentUseCaseMock = $this->createMock(ConfirmPaymentUseCase::class);
        $getWalletTransactionsUseCaseMock = $this->createMock(GetWalletTransactionsUseCase::class);
        $getWalletTransactionsUseCaseMock = $this->createMock(GetWalletTransactionsUseCase::class);

        $controller = new WalletSoapController($loadWalletUseCaseMock, $payWithWalletUseCaseMock, $confirmPaymentUseCaseMock, $getWalletBalanceUseCaseMock, $getWalletTransactionsUseCaseMock, $getWalletTransactionsUseCaseMock);

        $response = $controller->getWalletBalance($document, $celPhone);

        $this->assertTrue($response['success']);
        $this->assertEquals('Saldo obtenido correctamente.', $response['message']);
        $this->assertEquals($expectedBalance, $response['data']['balance']);
    }
}
