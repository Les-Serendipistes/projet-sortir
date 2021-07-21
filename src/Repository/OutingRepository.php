<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Outing;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
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

        $query  = $this->createQueryBuilder('outing')
                ->select('outing', 'campus', 'registered_users', 'state')
                ->innerJoin('outing.state', 'state')
                ->orWhere('state.label LIKE :ouverte')
                ->orWhere('state.label LIKE :activite')
                ->orWhere('state.label LIKE :creee')
                ->orWhere('state.label LIKE :cloture')
                ->orWhere('state.label LIKE :passee')
                ->setParameter('ouverte', '%Ouverte%')
                ->setParameter('activite', '%Activité en cours%')
                ->setParameter('creee', '%Créée%')
                ->setParameter('cloture', '%Cloturée%')
                ->setParameter('passee', '%Passée%')
                ->andWhere('outing.dateTimeStart > :now')
                ->setParameter('now', $now)
                ->innerJoin('outing.campus', 'campus')
                ->leftJoin('outing.registeredUsers', 'registered_users')
                ->leftJoin('registered_users.outings', 'ot')
            ;

        if ($search->campus) {
            $query

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
                ->orWhere('state.label LIKE :creee')
                ->setParameter('creee', 'Créée')
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
                ->andWhere(':user NOT MEMBER OF outing.registeredUsers')
                ->setParameter('user', $user);
        }
        if ($search->olds) {
            $query
                ->andWhere('state.label LIKE :passee')
                ->setParameter('passee', '%Passée%')
                ->orWhere('outing.dateTimeStart < :now')
                ->setParameter('now', $now);
        }
        return $query
            ->andWhere('outing.dateTimeStart > :now')
            ->setParameter('now', $now)
            ->orderBy('outing.dateTimeStart', 'DESC')
            ->getQuery()
            ->getResult();
    }

}
