<?php

namespace Src\Domain\Wallet;


interface WalletRepositoryInterface
{




    /**
     * Guardar un wallet asociado a un cliente
     *
     * @param Wallet $wallet
     * @return void
     */
    public function save(Wallet $wallet): void;

    /**
     *  buscar wallet por id.
     *
     * @param int $id
     * @return Wallet|null
     */
    public function findById(int $id): ?Wallet;


    /**
     * Buscar wallet por documento del cliente.
     *
     * @param string $document
     * @return Wallet|null
     */
    public function findByDocumentAndCelPhone(string $document, string $celPhone): ?Wallet;
}
