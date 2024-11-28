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


    public function load(ObjectManager $manager): void
    {
        $user1 = new Users();
        $user1->setName('test1');
        $user1->setEmail('test1@gmail.com');
        $password = 'test1234';
        $hashPass = $this->hasher->hashPassword($user1, $password);
        $user1->setPassword($hashPass);
        $manager->persist($user1);

        $user2 = new Users();
        $user2->setName('test2');
        $user2->setEmail('test2@gmail.com');
        $password = 'test1234';
        $hashPass = $this->hasher->hashPassword($user2, $password);
        $user2->setPassword($hashPass);
        $manager->persist($user2);

        $manager->flush();
    }



}