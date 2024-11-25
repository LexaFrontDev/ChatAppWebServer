<?php


namespace App\Service;

use App\Entity\Users;
use App\Entity\Messages;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Service\EncryptMessages\EncryptionService;


#[AsService]
class GetMessagesService
{
    private EncryptionService $encryptService;
    private EntityManagerInterface $entityManager;
    private Security $security;


    public function __construct(EncryptionService $encryptService, EntityManagerInterface $entityManager,Security $security)
    {
        $this->encryptService = $encryptService;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }


    public function getAllMessages()
    {
        $receiver = $this->security->getUser();

        if (!$receiver instanceof Users) {
            throw new \RuntimeException("Пользователь не аутентифицирован");
        }

        $messages = $this->entityManager->getRepository(Messages::class)
            ->findBy(['receiver' => $receiver]);

        $result = [];
        foreach ($messages as $message) {
            $sender = $message->getSender();
            $content = $message->getContent();
            $iv = $message->getIv();
            $messageU = $this->encryptService->decryptMessage($content, $iv);

            $result[] = [
                'sender' => [
                    'id' => $sender ? $sender->getId() : null,
                    'email' => $sender ? $sender->getEmail() : null,
                    'name' => $sender ? $sender->getName() : null,
                ],
                'id_message' => $message->getId(),
                'message' => $messageU,
                'timeSend' => $message->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }

        return $result;
    }
}
