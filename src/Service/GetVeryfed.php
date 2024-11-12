<?php


namespace App\Service;


use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;



#[AsService]
class GetVeryfed
{

    private EntityManagerInterface $entityManager;


    public function __construct( EntityManagerInterface $entityManager)
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