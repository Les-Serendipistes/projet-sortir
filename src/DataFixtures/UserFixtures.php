<?php


namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordEncoderInterface $hash;

    public const USER_REFERENCE = 'user';

    public function __construct(UserPasswordEncoderInterface $hash) {
        $this->hash = $hash;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        //Create 20 Users
        for ($i = 0; $i <= 200; $i++) {

            $faker = Factory::create('fr_FR');

            $user = new User();
            $user->setEmail(strtolower($faker->unique()->email))
                ->setRoles(['ROLE_USER'])
                ->setPassword($this->hash->encodePassword($user, 'ultracrepidarianisme'))
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setPseudo($faker->unique()->userName.$i)
                ->setPhone($faker->phoneNumber)
                ->setActif($faker->boolean(75))
                ->setPicture('default.png')
                ->setIsVerified($faker->boolean(75))
                ->setCampus($this->getReference(CampusFixtures::CAMPUS_REFERENCE.'_'.mt_rand(1,4)));

                $manager->persist($user);

                $this->addReference(self::USER_REFERENCE.'_'.$i, $user);
        }
            $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CampusFixtures::class,
        ];
    }
}