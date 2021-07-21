<?php

namespace App\Repository;

use App\Entity\OutingState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OutingState|null find($id, $lockMode = null, $lockVersion = null)
 * @method OutingState|null findOneBy(array $criteria, array $orderBy = null)
 * @method OutingState[]    findAll()
 * @method OutingState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OutingStateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OutingState::class);
    }

    // /**
    //  * @return OutingState[] Returns an array of OutingState objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OutingState
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
