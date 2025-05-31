<?php

namespace Tests\Feature\src\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Src\Domain\Client\Client;
use Src\Domain\Wallet\Wallet;
use Src\Infrastructure\Persistence\Doctrine\WalletRepository;
use Tests\TestCase;

class WalletRepositoryTest extends TestCase
{
    protected $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = app(EntityManagerInterface::class);
        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    /**
     * Prueba para validar la creacion de la wallet y que la busqueda por documento y telefono movil es correcta
     *
     */
    public function test_save_and_find_wallet_by_document_and_celphone()
    {

        $client = new Client('123456789', 'Jorge', 'Cortes', 'jorhcl@hotmail.com', '9981447710');
        $wallet = new Wallet($client);

        $repository = new WalletRepository($this->entityManager);
        $repository->save($wallet);

        $foundWallet = $repository->findByDocumentAndCelPhone('123456789', '9981447710');
        $this->assertNotNull($foundWallet);
        $this->assertEquals($wallet->getId(), $foundWallet->getId());
        $this->assertEquals('123456789', $foundWallet->getClient()->getDocument());
        $this->assertEquals('9981447710', $foundWallet->getClient()->getCelPhone());
    }
}
