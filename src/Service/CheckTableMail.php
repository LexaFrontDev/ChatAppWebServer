<?php


namespace App\Service;

use App\Entity\MailVeryfication;
use App\Singleton\EntityManagerSingleton;


#[AsService]
class CheckTableMail
{

    private EntityManagerSingleton $entityManager;


    public function __construct(EntityManagerSingleton $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function check($email)
    {
        $byEmail = $this->entityManager->getRepository(MailVeryfication::class)
            ->findOneBy(['email' => $email]);

        if ($byEmail) {
            return false;
        }

        return $byEmail;
    }

}