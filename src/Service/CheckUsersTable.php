<?php


namespace App\Service;


use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;



#[AsService]
class CheckUsersTable
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
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