<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function findAllQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('item');
    }

    public function findByUlidQuery(string $ulid): QueryBuilder
    {
        $qb = $this->findAllQuery();
        $qb->andWhere(
            $qb->expr()->eq("item.ulid", ':ulid')
        );
        $qb->setParameter('ulid', $ulid, 'ulid');

        return $qb;
    }
}
