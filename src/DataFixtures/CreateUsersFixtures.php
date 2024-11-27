<?php


namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\Users;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class CreateUsersFixtures extends Fixture
{

    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }


    public function load(ObjectManager $manager)
    {
        $user = new Users();
        $user->setName('test1');
        $user->setEmail('test1@gmail.com');
        $password = 'test1234';
        $hashPass  = $this->hasher->hashPassword($user, $password);
        $user->setPassword($hashPass);
        $manager->persist($user);
        $manager->flush();
    }


}