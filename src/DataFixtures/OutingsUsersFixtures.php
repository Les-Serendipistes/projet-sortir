<?php


namespace App\DataFixtures;


use App\Entity\Outing;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OutingsUsersFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i <= 43; $i++) {

            /**
             * @var Outing $users
             */
            $users = $this->getReference(UserFixtures::USER_REFERENCE.'_'.mt_rand(1,200));

            /**
             * @var User $outings
             */
            $outings = $this->getReference( OutingFixtures::OUTING_REFERENCE.'_'.mt_rand(1,40));

            $users->addOuting($outings);
            $outings->addRegisteredUser($users);


            $manager->persist($outings);
            $manager->persist($users);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            OutingFixtures::class,
            UserFixtures::class,
        ];
    }
}