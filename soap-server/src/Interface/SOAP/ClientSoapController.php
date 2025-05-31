<?php


namespace Src\Interface\SOAP;

use Src\Shared\Response\StandardResponse;
use Src\Application\UseCases\RegisterClientUseCase;
use Exception;


class ClientSoapController
{
    private RegisterClientUseCase $registerClientUseCase;

    public function __construct(RegisterClientUseCase $registerClientUseCase)
    {
        $this->registerClientUseCase = $registerClientUseCase;
    }

    /**
     * Register a new client via SOAP.
     *
     * @param string $document
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $celPhone
     * @return string
     */
    public function registerClient(
        string $document,
        string $firstName,
        string $lastName,
        string $email,
        string $celPhone
    ): array {
        try {
            $client = $this->registerClientUseCase->handle($document, $firstName, $lastName, $email, $celPhone);
            return StandardResponse::success(
                'Cliente registrado exitosamente.',
                [
                    'document' => $client->getDocument(),
                    'first_name' => $client->getFirstName(),
                    'last_name' => $client->getLastName(),
                    'email' => $client->getEmail(),
                    'cel_phone' => $client->getCelPhone()
                ]
            );
        } catch (Exception $e) {
            return StandardResponse::error(
                'Error al registrar el cliente: ' . $e->getMessage(),
                $e->getCode()
            );
        }
    }
}
