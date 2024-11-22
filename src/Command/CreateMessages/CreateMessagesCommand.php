<?php


namespace App\Command\CreateMessages;

use App\Entity\Messages;
use Doctrine\ORM\EntityManagerInterface;

class CreateMessagesCommand
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createMessages($sender, $receiver, $content){
        $message = new Messages();
        $message->setSender($sender);
        $message->setReceiver($receiver);
        $message->setContent($content);
        $this->entityManager->persist($message);
        $this->entityManager->flush();
        return $this;
    }

}