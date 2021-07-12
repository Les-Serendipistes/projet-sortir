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
    private $security;

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

        $query = $this->createQueryBuilder('o');
        $query = $query
            ->select('o');


        if (!empty($search->campus)) {
            $query
                ->leftJoin('o.campus', 'c')
                ->andWhere('o.campus = :campusId')
                ->setParameter('campusId', $search->campus);
        }
        if (!empty($search->q)) {
            $query
                ->andWhere('o.name LIKE :q')
                ->setParameter('q', '%'.$search->q.'%');
        }
        if (!empty($search->startDate) && !empty($search->endDate)) {
            $query
                ->where('o.dateTimeStart BETWEEN :startDate AND :endDate')
                ->setParameter('startDate', $search->startDate)
                ->setParameter('endDate', $search->endDate);
        }
        if(!empty($search->organizer)) {
            $query
                ->andWhere('o.organizerUser = :user')
                ->setParameter('user', $user);
        }
        if(!empty($search->registered)) {
            $query->andWhere('o.registeredUsers = :userId')
                ->leftJoin('c.users', 'u')
                ->where('u.id = :userId ')
                ->setParameter('userId', $user);
        }
        if(!empty($search->unregistered)) {
            $query->leftJoin('o.registeredUsers', 'reg')
                ->andWhere(':userId', 'NOT IN', 'o.registeredUsers')
                ->setParameter('userId', $user);
        }
        if(!empty($search->olds)) {
            $query->andWhere('o.dateTimeStart < :now')
                ->setParameter('now', $now);
        }
        return $query
            ->orderBy('o.dateTimeStart', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
