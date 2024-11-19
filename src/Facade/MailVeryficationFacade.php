<?php


namespace App\Facade;

use App\Entity\MailVeryfication;
use App\Singleton\EntityManagerSingleton;

class MailVeryficationFacade
{

    private $entityManagerSingleton;


    public function __construct(EntityManagerSingleton $entityManagerSingleton)
    {
        $this->entityManagerSingleton = $entityManagerSingleton;
    }


    public function createMail($email, $code){
        $newVerification = new MailVeryfication();
        $newVerification->setEmail($email);
        $newVerification->setCode($code);
        $newVerification->setCreatedAt(new \DateTime());
        $this->entityManagerSingleton->persist($newVerification);
        return $this;
    }


    public function isMailUnique($email)
    {
        $byEmail = $this->entityManagerSingleton->getRepository(MailVeryfication::class)
            ->findOneBy(['email' => $email]);

        if ($byEmail) {
            return false;
        }

        return $byEmail;
    }



}