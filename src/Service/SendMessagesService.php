<?php


namespace App\Service;

use App\Entity\Messages;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;

#[AsService]
class SendMessagesService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function sendMessages($senderId, $receiverId, string $content)
    {
        $sender = $this->entityManager->getRepository(Users::class)->find($senderId);
        $receiver = $this->entityManager->getRepository(Users::class)->find($receiverId);

        if (!$sender) {
            throw new \InvalidArgumentException("Отправитель не найден");
        }

        if (!$receiver) {
            throw new \InvalidArgumentException("Получатель не найден");
        }

        $message = new Messages();
        $message->setSender($sender);
        $message->setReceiver($receiver);
        $message->setContent($content);
        $this->entityManager->persist($message);
        $this->entityManager->flush();
        return true;
    }
}
