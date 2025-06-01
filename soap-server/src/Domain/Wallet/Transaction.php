<?php


namespace Src\Domain\Wallet;

use Src\Domain\Client\Client;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

use function PHPSTORM_META\type;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'transactions')]

class Transaction
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36, unique: true)]
    private string $id;

    #[ORM\ManyToOne(targetEntity: Wallet::class, inversedBy: 'transactions')]
    #[ORM\JoinColumn(name: 'wallet_id', referencedColumnName: 'id', nullable: false)]
    private Wallet $wallet;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $amount;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $description;


    #[ORM\Column(type: 'string', length: 6, unique: true, nullable: true)]
    private ?string $token = null;


    #[ORM\Column(type: 'string', length: 255)]
    private string $status;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $session_id = null;


    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTime $createdAt;

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTime $updatedAt;

    public function __construct(Wallet $wallet, float $amount, ?string $description = null)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->wallet = $wallet;
        $this->amount = $amount;
        $this->status = 'pending';
        $this->description = $description;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getWallet(): Wallet
    {
        return $this->wallet;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function getStatus(): string
    {
        return $this->status;
    }


    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
        $this->updatedAt = new \DateTime();
    }

    public function getToken(): ?string
    {
        return $this->token;
    }
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getSessionId(): ?string
    {
        return $this->session_id;
    }
    public function setSessionId(?string $session_id): void
    {
        $this->session_id = $session_id;
    }
}
