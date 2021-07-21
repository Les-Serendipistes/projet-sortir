<?php


namespace App\DataFixtures;


use App\Entity\Outing;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\Integer;

class OutingsUsersFixtures extends Fixture implements DependentFixtureInterface
{

    public const OUTING_USER_REFERENCE = 'usersOutings';

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i <= 43; $i++) {

            /**
             * @var int
             */
            $rand1 = mt_rand(1,40);

            /**
             * @var Outing $users
             */
            $users = $this->getReference(UserFixtures::USER_REFERENCE.'_'.$rand1);

            /**
             * @var int
             */
            $rand2 = mt_rand(1,40);
            /**
             * @var User $outings
             */
            $outings = $this->getReference( OutingFixtures::OUTING_REFERENCE.'_'.$rand2);


            $users->addOuting($outings);
            $outings->addRegisteredUser($users);


            $manager->persist($outings);
            $manager->persist($users);

            /**
             * @var int
             */
            $dif = ($rand1 - $rand2);

            $this->addReference(self::OUTING_USER_REFERENCE.'_'.$i, (object)$dif);
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