<?php

namespace App\Repository;

use App\Entity\Basket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class BasketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Basket::class);
    }

    public function findAllQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('basket');
    }

    public function findByUlidQuery(?string $ulid): QueryBuilder
    {
        $qb = $this->findAllQuery();
        $qb->andWhere(
            $qb->expr()->eq("basket.ulid", ':ulid')
        );
        $qb->setParameter('ulid', $ulid, 'ulid');

        return $qb;
    }

    public function findOneByUlid(string $ulid): ?object
    {
        return $this->findOneBy(
            [
                'ulid' => $ulid,
            ]
        );
    }

    public function sumWeight(string $ulid): float
    {
        $qb = $this->findByUlidQuery($ulid);

        $qb->select('SUM(i.weight)')
           ->innerJoin('basket.items', 'i')
        ;

        try {
            return $qb->getQuery()->getSingleScalarResult() ?? 0.0;
        } catch (NoResultException | NonUniqueResultException $e) {
            return 0.0;
        }
    }
}
