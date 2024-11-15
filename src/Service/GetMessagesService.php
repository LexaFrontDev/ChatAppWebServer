<?php


namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Messages;
use Psr\Log\LoggerInterface;

#[AsService]
class GetMessagesService
{
    private  $entityManager;
    private $logger;


    public function __construct(LoggerInterface $logger,EntityManagerInterface $entityManager)
    {
        $this->logger = $logger;
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
            $sender = $message->getSender();

            $result[] = [
                'sender' => [
                    'id' => $sender ? $sender->getId() : null,
                    'email' => $sender ? $sender->getEmail() : null,
                    'name' => $sender ? $sender->getName() : null,
                ],
                'message' => $message->getContent(),
                'timeSend' => $message->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }

        return $result;
    }
}