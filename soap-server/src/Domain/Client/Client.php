<?php


namespace Src\Domain\Client;

use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]

#[ORM\Table(name: 'clients')]

class Client
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 40, unique: true)]
    private string $document;

    #[ORM\Column(type: "string", length: 255)]
    private string $firstName;

    #[ORM\Column(type: "string", length: 255)]
    private string $lastName;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private string $email;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private ?string $celPhone;

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private \DateTime $createdAt;

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private \DateTime $updatedAt;


    public function __construct(
        string $document,
        string $firstName,
        string $lastName,
        string $email,
        ?string $celPhone = null
    ) {
        $this->document = $document;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->celPhone = $celPhone;

        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getDocument(): string
    {
        return $this->document;
    }
    public function getFirstName(): string
    {
        return $this->firstName;
    }
    public function getLastName(): string
    {
        return $this->lastName;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getCelPhone(): ?string
    {
        return $this->celPhone;
    }
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    public function setCelPhone(?string $celPhone): void
    {
        $this->celPhone = $celPhone;
    }
    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}
