<?php


namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Messages;

#[AsService]
class GetMessagesService
{
    private  $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllMessage($id)
    {
        $messages = $this->entityManager->getRepository(Messages::class)
            ->findBy(['receiver' => $id]);

        if (empty($messages)) {
            throw new \InvalidArgumentException("Сообщения для получателя не найдены");
        }

        $result = [];
        foreach ($messages as $message) {
            $result[] = [
                'sender' => $message->getSender(),
                'message' => $message->getContent(),
                'timeSend' => $message->getCreatedAt(),
            ];
        }

        return $result;
    }
}