<?php


namespace App\DataFixtures;


use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class StateFixtures extends Fixture
{
    private EntityManagerInterface $entityManager;
    public const STATE_REFERENCE = 'state';

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function load(ObjectManager $manager)
    {
        $this->saveState();

    }

    public function saveState() {

        $state = new State();
        $state->setLabel('Créée');
        $this->addReference(self::STATE_REFERENCE.'_'.'1',$state);
        $this->entityManager->persist($state);

        $state = new State();
        $state->setLabel('Ouverte');
        $this->addReference(self::STATE_REFERENCE.'_'.'2',$state);
        $this->entityManager->persist($state);

        $state = new State();
        $state->setLabel('Cloturée');
        $this->addReference(self::STATE_REFERENCE.'_'.'3',$state);
        $this->entityManager->persist($state);

        $state = new State();
        $state->setLabel('Activité en cours');
        $this->addReference(self::STATE_REFERENCE.'_'.'4',$state);
        $this->entityManager->persist($state);

        $state = new State();
        $state->setLabel('Passée');
        $this->addReference(self::STATE_REFERENCE.'_'.'5',$state);
        $this->entityManager->persist($state);

        $state = new State();
        $state->setLabel('Annulée');
        $this->addReference(self::STATE_REFERENCE.'_'.'6',$state);
        $this->entityManager->persist($state);

        $state = new State();
        $state->setLabel('Archivée');
        $this->addReference(self::STATE_REFERENCE.'_'.'7',$state);
        $this->entityManager->persist($state);


        $this->entityManager->flush();
    }

}