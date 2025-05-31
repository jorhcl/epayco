<?php

namespace Src\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Src\Domain\Client\Client;
use Src\Domain\Client\ClientRepositoryInterface;


class ClientRepository implements ClientRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(Client $client): void
    {
        $this->entityManager->persist($client);
        $this->entityManager->flush();
    }

    public function findByDocument(string $document): ?Client
    {
        return $this->entityManager->getRepository(Client::class)->findOneBy(['document' => $document]);
    }

    public function findByEmail(string $email): ?Client
    {
        return $this->entityManager->getRepository(Client::class)->findOneBy(['email' => $email]);
    }

    public function findByCelPhone(string $celPhone): ?Client
    {
        return $this->entityManager->getRepository(Client::class)->findOneBy(['celPhone' => $celPhone]);
    }
}
