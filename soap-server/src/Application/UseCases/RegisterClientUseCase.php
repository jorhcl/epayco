<?php

namespace Src\Application\UseCases;

use Src\Domain\Client\Client;
use Src\Domain\Client\RegisterClientService;
use Src\Domain\Wallet\Wallet;
use Src\Domain\Wallet\WalletRepositoryInterface;
use Src\Infrastructure\Persistence\Doctrine\WalletRepository;

class RegisterClientUseCase
{
    private RegisterClientService $service;
    private  WalletRepository $walletRepository;

    public function __construct(RegisterClientService $registerClientService, WalletRepository $walletRepository)
    {
        $this->service = $registerClientService;
        $this->walletRepository = $walletRepository;
    }

    /**
     * Registra un nuevo cliente y crea su wallet asociada
     *
     * @param string $document
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $celPhone
     * @return Client
     */
    public function handle(
        string $document,
        string $firstName,
        string $lastName,
        string $email,
        ?string $celPhone
    ): Client {

        $client = $this->service->register($document, $firstName, $lastName, $email, $celPhone);

        $wallet = new Wallet($client);
        $this->walletRepository->save($wallet);

        return $client;
    }
}
