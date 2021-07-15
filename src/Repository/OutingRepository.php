<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Outing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
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
    private EntityManagerInterface $entityManager;

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

        $query  = $this->createQueryBuilder('outing')
                ->select('outing', 'campus', 'registered_users')
                ->join('outing.campus', 'campus')
                ->leftJoin('outing.registeredUsers', 'registered_users');

        if ($search->campus) {
            $query
                ->join('outing.campus', 'c')
                ->andWhere('outing.campus = :campusId')
                ->setParameter('campusId', $search->campus);
        }
        if ($search->q) {
            $query
                ->andWhere('outing.name LIKE :q')
                ->setParameter('q', '%'.$search->q.'%');
        }
        if (($search->dateStart) || ($search->dateEnd)) {
            $query
                ->andWhere('outing.dateTimeStart >= :dateStart')
                ->andWhere('outing.dateTimeStart <= :dateEnd')
                ->setParameter('dateStart', $search->dateStart)
                ->setParameter('dateEnd', $search->dateEnd);
        }
        if ($search->organizer) {
            $query
                ->andWhere('outing.organizerUser = :org')
                ->setParameter('org', $user);
        }
        if ($search->registered) {
            $query
                ->andWhere(':user MEMBER OF outing.registeredUsers')
                ->setParameter('user', $user);
        }
        if ($search->unregistered ) {
            $query
                ->leftJoin('registered_users.outings', 'ot')
                ->andWhere(':user NOT MEMBER OF outing.registeredUsers')
                ->setParameter('user', $user);
        }
        if ($search->olds) {
            $query->andWhere('outing.dateTimeStart < :now')
                ->setParameter('now', $now);
        }
        return $query
            ->leftJoin('outing.state', 'state')
            ->orWhere('state.label LIKE :ouverte')
            ->orWhere('state.label LIKE :activite')
            ->orWhere('state.label LIKE :passee')
            ->setParameter('ouverte', "Ouverte")
            ->setParameter('activite', "Activité en cours")
            ->setParameter('passee', "Passée")
            ->orderBy('outing.dateTimeStart', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function nbSubscribers(Outing $outing)
    {
        $registeredUsers = $this->createQueryBuilder('o')
            ->leftJoin('o.registeredUsers', 'r')
            ->select('COUNT(r.id)')
            ->andWhere('r.id = :outingId')
            ->setParameter('outingId', $outing->getId())
            ->getQuery()
            ->getSingleScalarResult();
        return $registeredUsers;
    }
}
