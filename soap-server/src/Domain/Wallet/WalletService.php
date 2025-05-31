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

        $url = env('FRONTEND_URL');
        $subject = 'Token de transacción';
        $message = <<<EOT
            Gracias por iniciar una compra.

            Por favor, confirme su transacción usando el siguiente enlace:
            {$url}/confirmar?token={$token}&session_id={$sessionId}


                Este enlace es válido solo para esta sesión.
        EOT;

        Mail::raw($message, function ($message) use ($email, $subject) {
            $message->to($email)->subject($subject);
        });
    }
}
