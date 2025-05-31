<?php

namespace Src\Interface\SOAP;



class SoapFacadeController
{

    private WalletSoapController $walletSoapController;
    private ClientSoapController $clientSoapController;

    public function __construct(WalletSoapController $walletSoapController, ClientSoapController $clientSoapController)
    {
        $this->walletSoapController = $walletSoapController;
        $this->clientSoapController = $clientSoapController;
    }


    public function registerClient(
        string $document,
        string $firstName,
        string $lastName,
        string $email,
        string $celPhone
    ): array {

        $result = $this->clientSoapController->registerClient($document, $firstName, $lastName, $email, $celPhone);

        if ($result['success'] === 0) {
            return [
                'success' => 0,
                'error_code' => $result['error_code'],
                'message' => $result['message'],
            ];
        }
        if (!is_array($result)) {
            return [
                'success' => 0,
                'error_code' => 'UNEXPECTED_RESPONSE',
                'message' => $result,
            ];
        }

        return $result;
    }

    public function loadWallet(string $document, string $celPhone, float $amount): array
    {
        return $this->walletSoapController->loadWallet($document, $celPhone, $amount);
    }

    public function payWithWallet(string $document, string $celPhone, float $amount, string $description): array
    {
        return $this->walletSoapController->payWithWallet($document, $celPhone, $amount, $description);
    }
    public function confirmPayment(string $token, string $sessionId): array
    {
        return $this->walletSoapController->confirmPayment($token, $sessionId);
    }
    public function getWalletBalance(string $document, string $celPhone): array
    {
        return $this->walletSoapController->getWalletBalance($document, $celPhone);
    }

    public function getWalletTransactions(string $document, string $celPhone): array
    {
        return $this->walletSoapController->getWalletTransactions($document, $celPhone);
    }
}
