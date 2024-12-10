<?php


namespace App\Command\Create\CreateMessages;

use App\Entity\MessagesGroup;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\AuthService\TokenService;

class CreateMessagesGroupCommand implements createMessageInterfaceCommand
{

    private $tokenService;
    private $entityManager;

    public function __construct(TokenService $tokenService,EntityManagerInterface $entityManager)
    {
        $this->tokenService = $tokenService;
        $this->entityManager = $entityManager;
    }

    public function createMessages($sender, $receiver, string $encryptedContent, string $iv){
        $message = new MessagesGroup();
        $message->setSenderId($sender);
        $message->setGroupId($receiver);
        $message->setContent($encryptedContent);
        $message->setIv($iv);
        $this->entityManager->persist($message);
        $this->entityManager->flush();
        return $this;
    }

}