<?php


namespace App\Service;


use App\Entity\Users;
use App\Singleton\EntityManagerSingleton;


#[AsService]
class GetVeryfed
{

    private EntityManagerSingleton $entityManager;


    public function __construct(EntityManagerSingleton $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function getVeryFed($email){
        $user = $this->entityManager->getRepository(Users::class)->findOneBy([
            'email' => $email,
        ]);

        if($user->isVerified() === false){
            return false;
        }
        return true;
    }
}