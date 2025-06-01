<?php

namespace Src\Domain\Wallet;

use Exception;
use Illuminate\Support\Facades\Mail;
use Src\Domain\Client\Client;
use Src\Infrastructure\Persistence\Doctrine\WalletRepository;

class WalletService
{
    private WalletRepository $walletRepository;



    public function __construct(WalletRepository $walletRepository)
    {


        $this->walletRepository = $walletRepository;
    }

    /**
     * Crea una nueva wallet para el cliente.
     *
     * @param string $document
     * @return Wallet
     * @throws Exception
     */
    public function createWallet(Client $client): Wallet
    {
        if ($this->walletRepository->findByDocumentAndCelPhone($client->getDocument(), $client->getCelPhone())) {
            throw new Exception("Ya existe una wallet asociada al documento {$client->getDocument()}.");
        }

        $wallet = new Wallet($client);
        $this->walletRepository->save($wallet);
        return $wallet;
    }

    /**
     * Funcion para validar que  la wallet tiene saldo suficiente
     *
     * @param Wallet $wallet
     * @param float $amount
     * @return bool
     *
     */
    public function hasSufficientBalance(Wallet $wallet, float $amount): bool
    {
        return $wallet->getBalance() >= $amount;
    }
    /**
     * Busca una wallet por documento y celular.
     *
     * @param string $document
     * @param string $celPhone
     * @return Wallet|null
     */
    public function findByDocumentAndCelPhone(string $document, string $celPhone): ?Wallet
    {
        return $this->walletRepository->findByDocumentAndCelPhone($document, $celPhone);
    }


    public function sendTokenByEmail(string $email, string $token, string $sessionId): void
    {

        $url = env('FRONTEND_URL') . "/confirm/{$sessionId}";
        $subject = 'Informacion de Compra - Epayco Wallet';
        $message = <<<EOT
             <html>
        <body style="font-family: Arial, sans-serif; padding: 20px;">
            <h2>Confirmación de Compra</h2>
            <p>Gracias por iniciar una compra.</p>

            <p style="margin-top: 20px;">Tu token de confirmación es:</p>
            <p style="font-size: 32px; font-weight: bold; color: #2c3e50; margin: 10px 0;">{$token}</p>

            <p>También puedes confirmar directamente usando el siguiente botón:</p>

            <a href="{$url}"
                style="
                    display: inline-block;
                    background-color: #4CAF50;
                    color: white;
                    padding: 12px 24px;
                    text-align: center;
                    text-decoration: none;
                    font-size: 16px;
                    border-radius: 6px;
                    margin-top: 20px;
                ">
                Confirmar Pago
            </a>

            <p style="margin-top: 40px; font-size: 14px; color: #777;">
                Este enlace es válido solo para esta sesión.
            </p>
        </body>
        </html>
        EOT;

        Mail::html($message, function ($message) use ($email, $subject) {
            $message->to($email)->subject($subject);
        });
    }
}
