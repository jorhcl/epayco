<?php

namespace Tests\Unit\src\Application\UsesCases;

use PHPUnit\Framework\TestCase;

class GetWalletTransactionsUseCaseTest extends TestCase
{

    /**
     * Prueba para obtener las transacciones de la wallet del cliente.
     */
    public function test_handle_retrieves_wallet_transactions()
    {
        // Arrange
        $document = '123456789';
        $celPhone = '9981447710';

        $useCaseMock = $this->createMock(\Src\Application\UseCases\GetWalletTransactionsUseCase::class);
        $useCaseMock->expects($this->once())
            ->method('handle')
            ->with($document, $celPhone)
            ->willReturn([
                'transactions' => [
                    ['id' => 1, 'amount' => 100, 'date' => '2025-05-30 14:30:00'],
                    ['id' => 2, 'amount' => 200, 'date' => '2025-05-31 12:00:00'],
                ]
            ]);


        $result = $useCaseMock->handle($document, $celPhone);


        $this->assertIsArray($result);
        $this->assertArrayHasKey('transactions', $result);
        $this->assertCount(2, $result['transactions']);
    }
}
