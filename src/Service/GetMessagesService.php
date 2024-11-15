<?php


namespace App\Service;

use App\Entity\Users;
use App\Entity\Messages;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;

#[AsService]
class GetMessagesService
{
    private Security $security;
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;

    public function __construct(Security $security, LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }


    public function getAllMessages()
    {
        $receiver = $this->security->getUser();

        if (!$receiver instanceof Users) {
            throw new \RuntimeException("Пользователь не аутентифицирован");
        }

        $messages = $this->entityManager->getRepository(Messages::class)
            ->findBy(['receiver' => $receiver]);

//        if (empty($messages)) {
//            throw new \RuntimeException("Сообщения для пользователя не найдены,");
//        }


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
