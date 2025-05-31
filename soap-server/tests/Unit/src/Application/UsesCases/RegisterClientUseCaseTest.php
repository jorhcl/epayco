<?php

namespace Tests\Unit\src\Application\UsesCases;

use PHPUnit\Framework\TestCase;
use Src\Application\UseCases\RegisterClientUseCase;
use Src\Domain\Client\Client;
use Src\Domain\Client\RegisterClientService;
use Src\Domain\Wallet\Wallet;
use Src\Domain\Wallet\WalletRepositoryInterface;
use Src\Infrastructure\Persistence\Doctrine\WalletRepository;

class RegisterClientUseCaseTest extends TestCase
{

    /**
     *  Prueba para verificar que se crea la instancia del cliente y se registra correctamente.
     *
     */
    public function test_handle_registers_client_and_returns_client_instance()
    {
        $document = '123456789';
        $firstName = 'Jorge Humberto';
        $lastName = 'Cortes';
        $email = 'jorhcl@hotmail.com';
        $celPhone = '9981447710';

        $clientMock = $this->createMock(Client::class);

        $serviceMock = $this->createMock(RegisterClientService::class);
        $serviceMock->expects($this->once())
            ->method('register')
            ->with($document, $firstName, $lastName, $email, $celPhone)
            ->willReturn($clientMock);

        $walletRepositoryMock = $this->createMock(WalletRepository::class);

        $useCase = new RegisterClientUseCase($serviceMock, $walletRepositoryMock);

        $result = $useCase->handle($document, $firstName, $lastName, $email, $celPhone);

        $this->assertSame($clientMock, $result);
    }


    /**
     * Prueba para verificar que se haya creado la wallet asociada al cliente.
     *
     */

    public function test_handle_creates_wallet_for_client()
    {
        $document = '123456789';
        $firstName = 'Jorge Humberto';
        $lastName = 'Cortes';
        $email = 'jorhcl@hotmail.com';
        $celPhone = '9981447710';

        $clientMock = $this->createMock(Client::class);

        $serviceMock = $this->createMock(RegisterClientService::class);
        $serviceMock->method('register')->willReturn($clientMock);

        $walletRepositoryMock = $this->createMock(WalletRepository::class);
        $walletRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->callback(function ($wallet) use ($clientMock) {

                return $wallet instanceof Wallet
                    && $wallet->getClient() === $clientMock;
            }));

        $useCase = new RegisterClientUseCase($serviceMock, $walletRepositoryMock);

        $useCase->handle($document, $firstName, $lastName, $email, $celPhone);
    }
}
