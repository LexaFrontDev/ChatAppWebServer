<?php


namespace App\Command\Create\CreateMailVeryfication;

use App\Entity\MailVeryfication;
use App\Repository\MailVeryficationRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateMailCommand
{

    private $mailVerificationRepository;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager,MailVeryficationRepository $mailVerificationRepository)
    {
        $this->entityManager = $entityManager;
        $this->mailVerificationRepository = $mailVerificationRepository;
    }


    public function createMail($email, $code)
    {
        $newVerification = new MailVeryfication();
        $newVerification->setEmail($email);
        $newVerification->setCode($code);
        $newVerification->setCreatedAt(new \DateTime());
        $this->entityManager->persist($newVerification);
        return $this;
    }


}