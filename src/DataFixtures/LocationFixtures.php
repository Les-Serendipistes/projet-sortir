<?php


namespace App\DataFixtures;


use App\Entity\Location;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class LocationFixtures extends Fixture implements DependentFixtureInterface
{

    private EntityManagerInterface $entityManager;
    public const LOCATION_REFERENCE = 'locations';

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function load(ObjectManager $manager)
    {
        $this->saveLocation();
    }

    
    public function saveLocation() {

        $faker = Factory::create('fr_FR');

        $location = new Location();
        $location->setName('Cinéma')
            ->setAddress($faker->address)
            ->setCity($this->getReference(CityFixtures::CITY_REFERENCE.'_'.mt_rand(1,20)))
            ->setLongitude($faker->longitude)
            ->setLatitude($faker->latitude);
        $this->entityManager->persist($location);
        $this->addReference(self::LOCATION_REFERENCE.'_'.'1',$location);

        $location = new Location();
        $location->setName('Theatre')
            ->setAddress($faker->address)
            ->setCity($this->getReference(CityFixtures::CITY_REFERENCE.'_'.mt_rand(1,20)))
            ->setLongitude($faker->longitude)
            ->setLatitude($faker->latitude);
        $this->entityManager->persist($location);
        $this->addReference(self::LOCATION_REFERENCE.'_'.'2',$location);

        $location = new Location();
        $location->setName('Concert')
            ->setAddress($faker->address)
            ->setCity($this->getReference(CityFixtures::CITY_REFERENCE.'_'.mt_rand(1,20)))
            ->setLongitude($faker->longitude)
            ->setLatitude($faker->latitude);
        $this->entityManager->persist($location);
        $this->addReference(self::LOCATION_REFERENCE.'_'.'3',$location);

        $location = new Location();
        $location->setName("Bowling")
            ->setAddress($faker->address)
            ->setCity($this->getReference(CityFixtures::CITY_REFERENCE.'_'.mt_rand(1,20)))
            ->setLongitude($faker->longitude)
            ->setLatitude($faker->latitude);
        $this->entityManager->persist($location);
        $this->addReference(self::LOCATION_REFERENCE.'_'.'4',$location);

        $location = new Location();
        $location->setName("Bar")
            ->setAddress($faker->address)
            ->setCity($this->getReference(CityFixtures::CITY_REFERENCE.'_'.mt_rand(1,20)))
            ->setLongitude($faker->longitude)
            ->setLatitude($faker->latitude);
        $this->entityManager->persist($location);
        $this->addReference(self::LOCATION_REFERENCE.'_'.'5',$location);
    
        $this->entityManager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CityFixtures::class
        ];
    }

}