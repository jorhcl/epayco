<?php

namespace Src\Domain\Client;

use Exception;
use Src\Infrastructure\Persistence\Doctrine\ClientRepository;

class RegisterClientService
{
    private ClientRepository $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * Register a new client.
     *
     * @param string $document
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $celPhone
     * @return Client
     * @throws Exception
     */
    public function register(
        string $document,
        string $firstName,
        string $lastName,
        string $email,
        ?string $celPhone
    ): Client {
        if ($this->clientRepository->findByDocument($document)) {
            throw new Exception("Ya existe un cliente con el documento {$document}.");
        }

        if ($this->clientRepository->findByEmail($email)) {
            throw new Exception("Ya existe un cliente con el email {$email}.");
        }

        if ($celPhone && $this->clientRepository->findByCelPhone($celPhone)) {
            throw new Exception("Ya existe un cliente con el telefono mobil {$celPhone}.");
        }

        $client = new Client($document, $firstName, $lastName, $email, $celPhone);
        $this->clientRepository->save($client);

        return $client;
    }
}
