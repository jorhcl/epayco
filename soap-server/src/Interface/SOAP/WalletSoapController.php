<?php

namespace Src\Interface\SOAP;

use Src\Shared\Response\StandardResponse;
use Src\Application\UseCases\LoadWalletUseCase;
use Exception;
use Illuminate\Support\Facades\Log as FacadesLog;
use Src\Application\UseCases\ConfirmPaymentUseCase;
use Src\Application\UseCases\GetWalletBalanceUseCase;
use Src\Application\UseCases\GetWalletTransactionsUseCase;
use Src\Application\UseCases\PayWithWalletUseCase;

class WalletSoapController
{
    private LoadWalletUseCase $loadWalletUseCase;
    private PayWithWalletUseCase $payWithWalletUseCase;
    private ConfirmPaymentUseCase $confirmPaymentUseCase;
    private GetWalletBalanceUseCase $getWalletBalanceUseCase;
    private GetWalletTransactionsUseCase $getWalletTransactionsUseCase;


    public function __construct(
        LoadWalletUseCase $loadWalletUseCase,
        PayWithWalletUseCase $payWithWalletUseCase,
        ConfirmPaymentUseCase $confirmPaymentUseCase,
        GetWalletBalanceUseCase $getWalletBalanceUseCase,
        GetWalletTransactionsUseCase $getWalletTransactionsUseCase
    ) {
        $this->loadWalletUseCase = $loadWalletUseCase;
        $this->payWithWalletUseCase = $payWithWalletUseCase;
        $this->confirmPaymentUseCase = $confirmPaymentUseCase;
        $this->getWalletBalanceUseCase = $getWalletBalanceUseCase;
        $this->getWalletTransactionsUseCase = $getWalletTransactionsUseCase;
    }

    public function loadWallet(string $document, string $celPhone, float $amount): array
    {
        try {
            $wallet = $this->loadWalletUseCase->handle($document, $celPhone, $amount);

            FacadesLog::info('Wallet loaded successfully', [
                $wallet
            ]);

            if (!$wallet) {
                return StandardResponse::error(
                    message: 'No se encontró la wallet para el documento y teléfono proporcionados.',
                    code: 404,

                );
            }


            return StandardResponse::success(
                'Saldo agregado correctamente.',
                [
                    'balance' => $wallet->getBalance(),
                ]
            );
        } catch (Exception $e) {
            return StandardResponse::error(
                message: 'Error al cargar la wallet: ' . $e->getMessage(),
                errorCode: $e->getCode(),
                code: 500
            );
        }
    }

    public function payWithWallet(string $document, string $celPhone, float $amount, string $description): array
    {
        try {
            $result = $this->payWithWalletUseCase->handle($document, $celPhone, $amount, $description);

            if ($result['success'] === 0) {
                return StandardResponse::error(
                    message: $result['message'],
                    errorCode: $result['error_code'],
                    code: 500
                );
            }

            return StandardResponse::success(
                'Compra iniciada ',
                [
                    'transaction_token' => $result['transaction']['token'],
                    'balance' => $result['wallet']['balance'],
                ]
            );
        } catch (Exception $e) {
            return StandardResponse::error(
                message: 'Error al iniciar la compra: ' . $e->getMessage(),
                errorCode: $e->getCode(),
                code: 500
            );
        }
    }

    public function confirmPayment(string $token, string $sessionId): array
    {
        try {
            $result = $this->confirmPaymentUseCase->handle($token, $sessionId);

            if ($result['success'] === 0) {
                return StandardResponse::error(
                    message: $result['message'],
                    errorCode: $result['error_code'],
                    code: 500
                );
            }

            return StandardResponse::success(
                'Pago confirmado exitosamente.',
                [
                    'wallet_balance' => $result['wallet_balance'],
                ]
            );
        } catch (Exception $e) {
            return StandardResponse::error(
                message: 'Error al confirmar el pago: ' . $e->getMessage(),
                errorCode: $e->getCode(),
                code: 500
            );
        }
    }


    public function getWalletBalance(string $document, string $celPhone): array
    {
        try {
            $balance = $this->getWalletBalanceUseCase->handle($document, $celPhone);

            if ($balance === null) {
                return StandardResponse::error(
                    message: 'No se encontró la wallet para el documento y teléfono proporcionados.',
                    code: 404
                );
            }

            return StandardResponse::success(
                'Saldo obtenido correctamente.',
                [
                    'balance' => $balance,
                ]
            );
        } catch (Exception $e) {
            return StandardResponse::error(
                message: 'Error al obtener el saldo de la wallet: ' . $e->getMessage(),
                errorCode: $e->getCode(),
                code: 500
            );
        }
    }

    public function getWalletTransactions(string $document, string $celPhone): array
    {
        try {
            $transactions = $this->getWalletTransactionsUseCase->handle($document, $celPhone);

            if (empty($transactions)) {
                return StandardResponse::error(
                    message: 'No se encontraron transacciones para la wallet proporcionada.',
                    code: 404
                );
            }

            return StandardResponse::success(
                'Transacciones obtenidas correctamente.',
                [
                    'transactions' => $transactions,
                ]
            );
        } catch (Exception $e) {
            return StandardResponse::error(
                message: 'Error al obtener las transacciones de la wallet: ' . $e->getMessage(),
                errorCode: $e->getCode(),
                code: 500
            );
        }
    }
}
