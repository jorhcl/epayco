<?php

namespace Tests\Feature\src\Interface\SOAP;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Src\Interface\SOAP\ClientSoapController;
use Src\Application\UseCases\RegisterClientUseCase;
use Src\Domain\Client\Client;
use Tests\TestCase;
use Exception;

class ClientSoapControllerTest extends TestCase
{
    /**
     * Prueba para validar el servicio SOAP para el registro de clientes
     *
     */

    public function test_register_client_success()
    {
        $mockUseCase = $this->createMock(RegisterClientUseCase::class);
        $mockClient = $this->createMock(Client::class);

        $mockClient->method('getDocument')->willReturn('123456789');
        $mockClient->method('getFirstName')->willReturn('Jorge');
        $mockClient->method('getLastName')->willReturn('Cortes');
        $mockClient->method('getEmail')->willReturn('jorhcl@hotmail.com');
        $mockClient->method('getCelPhone')->willReturn('9981447710');

        $mockUseCase->method('handle')->willReturn($mockClient);

        $controller = new ClientSoapController($mockUseCase);

        $response = $controller->registerClient('123456789', 'Jorge', 'Cortes', 'jorhcl@hotmail.com', '9981447710');

        $this->assertTrue($response['success']);
        $this->assertEquals('Cliente registrado exitosamente.', $response['message']);
        $this->assertEquals('123456789', $response['data']['document']);
    }

    public function test_register_client_error()
    {
        $mockUseCase = $this->createMock(RegisterClientUseCase::class);
        $mockUseCase->method('handle')->willThrowException(new Exception('Error de prueba', 500));

        $controller = new ClientSoapController($mockUseCase);

        $response = $controller->registerClient('123456789', 'Jorge', 'Cortes', 'jorhcl@hotmail.com', '9981447710');

        $this->assertFalse($response['success']);
        $this->assertStringContainsString('Error al registrar el cliente', $response['message']);
        $this->assertEquals(500, $response['code']);
    }
}
