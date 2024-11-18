<?php


namespace App\Service;


use App\Entity\Users;

use App\Singleton\EntityManagerSingleton;


#[AsService]
class CheckUsersTable
{

    private EntityManagerSingleton $entityManager;


    public function __construct(EntityManagerSingleton $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function check($name, $email)
    {
        $userByName = $this->entityManager->getRepository(Users::class)->findOneBy(['name' => $name]);
        $userByEmail = $this->entityManager->getRepository(Users::class)->findOneBy(['email' => $email]);

        if ($userByName || $userByEmail) {
            return false;
        }

        return true;
    }

}