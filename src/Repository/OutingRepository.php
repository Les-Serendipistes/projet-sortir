<?php

namespace App\Repository;

use App\Entity\Outing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Outing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Outing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Outing[]    findAll()
 * @method Outing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OutingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Outing::class);
    }

    public function findByDateTimeStart(int $page): Paginator
    {

        $queryBuilder = $this->createQueryBuilder('outing');
        $queryBuilder ->orderBy('outing.dateTimeStart', 'DESC');
        $queryBuilder->leftJoin('outing.organizerUser', 'org');
        $queryBuilder->addSelect('org');

        $query = $queryBuilder->getQuery();
        $limit = 20;
        $offset = ($page -1) * $limit;
        $query->setFirstResult($offset);
        $query->setMaxResults($limit);
        return new Paginator($query);
    }
}
