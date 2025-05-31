<?php

namespace Src\Domain\Wallet;

use Src\Domain\Client\Client;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'wallets')]

class Wallet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\OneToOne(targetEntity: Client::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'client_id', referencedColumnName: 'id', nullable: false)]
    private Client $client;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, options: ['default' => 0.00])]
    private float $balance = 0.00;

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTime $createdAt;

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTime $updatedAt;


    private array $transactions = [];




    public function __construct(Client $client)
    {

        $this->client = $client;
        $this->balance = 0.00;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function load(float $amount): void
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('El monto debe ser mayor a cero.');
        }

        $this->balance += $amount;
        $this->updatedAt = new \DateTime();
    }

    public function withdraw(float $amount): void
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('El monto debe ser mayor a 0.');
        }

        if ($amount > $this->balance) {
            throw new \RuntimeException('Saldo insuficiente.');
        }

        $this->balance -= $amount;
        $this->updatedAt = new \DateTime();
    }


    public function addTransaction(Transaction $transaction): void
    {
        $this->transactions[] = $transaction;
        $this->updatedAt = new \DateTime();
    }
    public function getTransactions(): array
    {
        return $this->transactions;
    }
}
