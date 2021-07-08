<?php


namespace App\DataFixtures;


use App\Entity\Outing;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class OutingFixtures extends Fixture implements DependentFixtureInterface
{

    public const OUTING_REFERENCE = 'outing';

    public function load(ObjectManager $manager)
    {
        //Create 20 Outings
        for ($o = 0; $o <= 40; $o++) {

            $faker = Factory::create('fr_FR');

            $outing = new Outing();
            $outing->setName('Sortie '.$o)
                ->setOrganizerUser($this->getReference(UserFixtures::USER_REFERENCE.'_'.mt_rand(1, 20)))
                ->setCampus($this->getReference(CampusFixtures::CAMPUS_REFERENCE.'_'.mt_rand(1,4)))
                ->setLocation($this->getReference(LocationFixtures::LOCATION_REFERENCE.'_'.mt_rand(1,5)))
                ->setState($this->getReference(StateFixtures::STATE_REFERENCE.'_'.mt_rand(1,6)))
                ->setOutingReport('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum')
                ->setMaxNbPart($faker->numberBetween(5, 20));

            $dateTimeStart = $faker->dateTimeBetween(startDate: '-60 days', endDate: '+60 days');
            $outing->setDateTimeStart($dateTimeStart);

            $limitDateInscription = date_sub($dateTimeStart, date_interval_create_from_date_string('- 3 days'));
            $outing->setLimitDateInscription($limitDateInscription)
                ->setDuration($faker->numberBetween(60, 360));

            $manager->persist($outing);

            $this->addReference(self::OUTING_REFERENCE.'_'.$o, $outing);
        }
        $manager->flush();
    }


    public function getDependencies(): array
    {
        return [
            CampusFixtures::class,
            UserFixtures::class,
            LocationFixtures::class,
            StateFixtures::class
        ];
    }
}