<?php


namespace Src\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Src\Domain\Wallet\Wallet;
use Src\Domain\Wallet\WalletRepositoryInterface;

class WalletRepository implements WalletRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(Wallet $wallet): void
    {
        $this->entityManager->persist($wallet);
        $this->entityManager->flush();
    }

    public function findById(int $id): ?Wallet
    {
        return $this->entityManager->getRepository(Wallet::class)->find($id);
    }

    public function findByDocumentAndCelPhone(string $document, string $celPhone): ?Wallet
    {
        $query = $this->entityManager->createQueryBuilder();
        $query->select('w')
            ->from(Wallet::class, 'w')
            ->innerJoin('w.client', 'c')
            ->where('c.document = :document')
            ->andWhere('c.celPhone = :celPhone')
            ->setParameter('document', $document)
            ->setParameter('celPhone', $celPhone);


        $query = $query->getQuery();

        return $query->getOneOrNullResult();
    }
}
