<?php

namespace App\Repository;

use App\Document\Charge;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\Persistence\ManagerRegistry;

class ChargeRepository extends ServiceDocumentRepository{
    public function __construct(ManagerRegistry $registry){
        parent::__construct($registry, Charge::class);
    }

    /**
     * @return Charge[]
     */
    public function findPendingChargesDueBefore(\DateTimeImmutable $date): array{
        return $this->createQueryBuilder()
            ->field('status')->equals('PENDENTE')
            ->field('dueDate')->lte($date)
            ->getQuery()
            ->execute()
            ->toArray();
    }
}