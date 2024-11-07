<?php


namespace App\Service;

use App\Entity\MailVeryfication;
use Doctrine\ORM\EntityManagerInterface;

#[AsService]
class CheckTableMail
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function check($email)
    {
        $byEmail = $this->entityManager->getRepository(MailVeryfication::class)->findOneBy(['email' => $email]);

        if ($byEmail) {
            return false;
        }

        return $byEmail;
    }

}