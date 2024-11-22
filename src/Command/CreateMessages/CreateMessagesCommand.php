<?php


namespace App\Command\CreateMessages;

use App\Entity\Messages;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\TokenService;


class CreateMessagesCommand
{

    private $tokenService;
    private $entityManager;

    public function __construct(TokenService $tokenService,EntityManagerInterface $entityManager)
    {
        $this->tokenService = $tokenService;
        $this->entityManager = $entityManager;
    }

    public function createMessages($sender, $receiver, string $encryptedContent, string $iv){
        $message = new Messages();
        $message->setSender($sender);
        $message->setReceiver($receiver);
        $message->setContent($encryptedContent);
        $message->setIv($iv);
        $this->entityManager->persist($message);
        $this->entityManager->flush();
        return $this;
    }

}