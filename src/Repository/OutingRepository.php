<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Outing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;
use Symfony\Component\Security\Core\Security;

/**
 * @method Outing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Outing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Outing[]    findAll()
 * @method Outing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OutingRepository extends ServiceEntityRepository
{
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(ManagerRegistry $registry, Security $security) {
        parent::__construct($registry, Outing::class);
        $this->security = $security;
    }

    /**
     * Get event with search engine.
     * @param SearchData $search
     * @return array
     */
    public function findSearch(SearchData $search): array
    {

        $now = new DateTime();

        $user = $this->security->getUser();

        $queryBuilder  = $this->createQueryBuilder('o');
        $query  = $this->createQueryBuilder('o');


        $userQuery = $this->createQueryBuilder('out')
            ->select('out.id')
            ->innerJoin('out.registeredUsers', 'rg')
            ->andWhere('rg.id = :userId')
            ->setParameter('userId', $user);

        $excludedOutings = implode(",", array_map(function ($exclude) {
            return $exclude['userId'];
        }, $userQuery->getQuery()->getResult()));

        if ($search->campus) {
            $query
                ->join('o.campus', 'c')
                ->andWhere('o.campus = :campusId')
                ->setParameter('campusId', $search->campus);
        }
        if ($search->q) {
            $query
                ->andWhere('o.name LIKE :q')
                ->setParameter('q', '%'.$search->q.'%');
        }
        if (($search->dateStart) || ($search->dateEnd)) {
            $query
                ->andWhere('o.dateTimeStart >= :dateStart')
                ->andWhere('o.dateTimeStart <= :dateEnd')
                ->setParameter('dateStart', $search->dateStart)
                ->setParameter('dateEnd', $search->dateEnd);
        }
        if ($search->organizer) {
            $query
                ->andWhere('o.organizerUser = :org')
                ->setParameter('org', $user);
        }
        if ($search->registered) {
            $query
                ->select('o', 'ou', 'us')
                ->join('o.registeredUsers', 'us')
                ->leftJoin('us.outings', 'ou')
                ->andWhere('us.outings = ou.registeredUsers')
                ->andWhere('ou.id = :userId')
                ->setParameter('userId', $user);
        }
        if ($search->unregistered && $excludedOutings) {
            $query
                ->andWhere($queryBuilder->expr()->notIn('o.id', $excludedOutings));
        }
        if ($search->olds) {
            $query->andWhere('o.dateTimeStart < :now')
                ->setParameter('now', $now);
        }
        return $query
            ->orderBy('o.dateTimeStart', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
