<?php


namespace App\Service;

use App\Entity\Users;
use App\Entity\Messages;
use App\Singleton\EntityManagerSingleton;
use Symfony\Bundle\SecurityBundle\Security;

#[AsService]
class GetMessagesService
{
    private EntityManagerSingleton $entityManagerSingleton;
    private Security $security;

    public function __construct(EntityManagerSingleton $entityManagerSingleton,Security $security)
    {
        $this->entityManagerSingleton = $entityManagerSingleton;
        $this->security = $security;
    }


    public function getAllMessages()
    {
        $receiver = $this->security->getUser();

        if (!$receiver instanceof Users) {
            throw new \RuntimeException("Пользователь не аутентифицирован");
        }

        $messages = $this->entityManagerSingleton->getRepository(Messages::class)
            ->findBy(['receiver' => $receiver]);

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
