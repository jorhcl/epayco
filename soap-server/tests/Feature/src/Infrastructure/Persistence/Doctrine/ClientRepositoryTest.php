<?php

namespace Tests\Feature\src\Infrastructure\Persistence\Doctrine;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Src\Infrastructure\Persistence\Doctrine\ClientRepository;
use Src\Domain\Client\Client;
use Doctrine\ORM\EntityManagerInterface;

class ClientRepositoryTest extends TestCase
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
     *  Prueba para validar la creacion del cliente y  que la busqueda por documento es correcta
     *
     */
    public function test_create_and_find_client()
    {
        $repository = new ClientRepository($this->entityManager);

        $client = new Client(
            '123456789',
            'Jorge ',
            'Cortes',
            'jorhcl@hotmail.com',
            '9981447710'
        );
        $repository->save($client);
        $fetchedClient = $repository->findByDocument('123456789');

        $this->assertNotNull($fetchedClient);
        $this->assertEquals('123456789', $fetchedClient->getDocument());
        $this->assertEquals('Jorge ', $fetchedClient->getFirstName());
    }
}
