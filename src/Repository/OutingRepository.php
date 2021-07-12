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
                    ->setParameter('campusId', '$campus');
            }
            if (!empty($search->q)) {
                $query
                    ->andWhere('o.name LIKE :q')
                    ->setParameter('q', '%'.$search->q.'%');
            }
            if(!empty($search->startDate) && !empty($search->endDate)) {
                if (($search->startDate > $now) &&  ($search->endDate < $now)) {
                    $query
                        ->andWhere('o.dateTimeStart', 'o','WITH','o.dateTimeStart BETWEEN :startDate AND :endDate')
                        ->setParameter('startDate', $search->startDate, 'DateTime')
                        ->setParameter('endDate', $search->endDate, 'DateTime');
                }
            }
            if(!empty($search->organizer)) {
                $query
                    ->andWhere('o.organizerUser = :userId')
                    ->setParameter('userId', $search->organizer);
            }
            if(!empty($search->registered)) {
                $query->andWhere('o.registeredUsers = :userId')
                    ->leftJoin('c.users', 'u')
                    ->where('u.id = :userId ')
                    ->setParameter('userId', $search->registered);
            }
            if(!empty($search->unregistered)) {
                $query->leftJoin('o.registeredUsers', 'reg')
                    ->andWhere(':userId', 'NOT IN', 'o.registeredUsers')
                    ->setParameter('userId', $search->unregistered);
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


    /*

    public function findOutings($filters)
    {
        $now = new \DateTime();

        if (empty($filters)) {
            $search = $this->createQueryBuilder('o')
                ->andWhere('o.dateTimeStart >= :now')
                ->setParameter('now', $now);
        } else {
            $dateFormat = 'd/m/Y H:i';
            $search = $this->createQueryBuilder('o')
                ->leftJoin('o.registeredUsers', 'reg');

            if ($filters['campus'] != null) {
                $search->leftJoin('o.organizerUser', 'org')
                    ->andWhere('org.campus', ':campusId')
                    ->setParameter('campusId', $filters['campus']);
            }

            if ($filters['nameContains'] != null) {
                $search->andWhere('o.name LIKE :nameContains')
                    ->setParameter('nameContains', '%' . $filters['nameContains'] . '%');
            }

            if ($filters['dateTimeStart'] != null) {
                $dateTimeStart = date_create_from_format($dateFormat, $filters['dateTimeStart']);
                $search->andWhere('o.dateTimeStart >= :dateTimeStart')
                    ->setParameter('dateTimeStart', $dateTimeStart);
            }

            if ($filters['dateTimeEnd'] != null) {
                $dateTimeEnd = date_create_from_format($dateFormat, $filters['dateTimeEnd']);
                $search->andWhere('o.dateTimeEnd <= :dateTimeEnd')
                    ->setParameter('dateTimeEnd', $dateTimeEnd);
            }

            $queryCheckBoxes = $this->createQueryBuilder('oo')
                ->leftJoin('oo.registeredUsers', 'oor');

            if ($filters['userId'] != null) {
                if ($filters['organizerUser']) {
                    $queryCheckBoxes->orWhere('oo.organizerUser = :orgId');
                    $search->setParameter('orgId', $filters['orgId']);
                }
                if ($filters['registeredUser']) {
                    $queryCheckBoxes->orWhere('oo.id = :userId');
                    $search->setParameter('userId', $filters['userId']);
                }
                if ($filters['unregistered']) {
                    $outingsId = $this->createQueryBuilder('oi')
                        ->leftJoin('oi.registeredUsers', 'regs')
                        ->where($queryCheckBoxes->expr()->eq('oi.id', $filters['userId']));
                }
                if ($filters['past']) {
                    $queryCheckBoxes->orWhere('oo.dateTimeStart <= :now');
                    $search->setParameter('now', $now);
                }
            }
            $search->andWhere($search->expr()
                ->in('s.id', $queryCheckBoxes->getDQL()));
        }
        return $search
            ->orderBy('o.dateTimeStart', 'DESC')
            ->getQuery()
            ->getResult();
    }
   */
}
