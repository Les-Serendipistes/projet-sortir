<?php


namespace App\DataFixtures;


use App\Entity\Campus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class CampusFixtures extends Fixture
{
    private EntityManagerInterface $entityManager;
    public const CAMPUS_REFERENCE = 'campus';

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function load(ObjectManager $manager)
    {
        $this->saveCampus();
    }

    public function saveCampus() {

        $campus = new Campus();
        $campus->setName('ENI-Rennes');
        $this->entityManager->persist($campus);
        $this->addReference(self::CAMPUS_REFERENCE.'_'.'1', $campus);

        $campus1 = new Campus();
        $campus1->setName('ENI-Nantes');
        $this->entityManager->persist($campus1);
        $this->addReference(self::CAMPUS_REFERENCE.'_'.'2', $campus1);

        $campus2 = new Campus();
        $campus2->setName('ENI-Quimper');
        $this->entityManager->persist($campus2);
        $this->addReference(self::CAMPUS_REFERENCE.'_'.'3', $campus2);

        $campus3 = new Campus();
        $campus3->setName("ENI-Niort");
        $this->entityManager->persist($campus3);
        $this->addReference(self::CAMPUS_REFERENCE.'_'.'4', $campus3);

        $this->entityManager->flush();
    }
}