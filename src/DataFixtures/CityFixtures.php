<?php


namespace App\DataFixtures;


use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CityFixtures extends Fixture
{

    public const CITY_REFERENCE = 'city';

    public function load(ObjectManager $manager)
    {
        //Create 20 cities
        for ($c = 0; $c <= 56; $c++) {

            $faker = Factory::create('fr_FR');

            $city = new City();
            $city->setName($faker->city)
                ->setPostCode($faker->postcode);

            $manager->persist($city);

            $this->addReference(self::CITY_REFERENCE.'_'.$c, $city);
        }
        $manager->flush();
    }

}