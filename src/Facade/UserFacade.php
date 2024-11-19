<?php


namespace App\Facade;

use App\Entity\Users;
use App\Singleton\EntityManagerSingleton;

class UserFacade
{
    private $entityManagerSingleton;

    public function __construct(EntityManagerSingleton $entityManagerSingleton) {
        $this->entityManagerSingleton = $entityManagerSingleton;
    }

    public function createUser(string $name, string $email)
    {
        $users = new Users();
        $users->setName($name);
        $users->setEmail($email);
        $this->entityManagerSingleton->save($users);
        return $users;
    }

    public function isUserUnique(string $name, string $email): bool
    {
        $userByName = $this->entityManagerSingleton->getRepository(Users::class)->findOneBy(['name' => $name]);
        $userByEmail = $this->entityManagerSingleton->getRepository(Users::class)->findOneBy(['email' => $email]);

        return !$userByName && !$userByEmail;
    }

    public function isVerified(string $email): bool
    {
        $user = $this->entityManagerSingleton->getRepository(Users::class)->findOneBy(['email' => $email]);

        return $user && $user->isVerified();
    }
}
