<?php

namespace Src\Domain\Client;

interface ClientRepositoryInterface
{

    /**
     * Guearda el Cliente .
     *
     * @param Client $client
     * @return void
     */
    public function save(Client $client): void;

    /**
     * Busca el cliente por documento.
     *
     * @param string $document
     * @return Client|null
     */
    public function findByDocument(string $document): ?Client;

    /**
     * Busca cliente por e-mail.
     *
     * @param Client $client
     * @return void
     */
    public function findByEmail(string $email): ?Client;

    /**
     * Busca cliente por telefono mobil.
     *
     * @param string $celPhone
     * @return Client|null
     */
    public function findByCelPhone(string $celPhone): ?Client;
}
