<?php


namespace App\Service;

use App\Entity\Messages;
use App\Entity\Users;
use App\Singleton\EntityManagerSingleton;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

#[AsService]
class SendMessagesService
{
    private EntityManagerSingleton $entityManager;
    private Security $security;

    public function __construct(EntityManagerSingleton $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function sendMessages(int $receiverId, string $content)
    {
        $sender = $this->security->getUser();

        if (!$sender instanceof Users) {
            throw new UnauthorizedHttpException('Bearer', 'Отправитель не авторизован');
        }

        $receiver = $this->entityManager->getRepository(Users::class)->find($receiverId);

        if (!$receiver) {
            throw new \InvalidArgumentException("Получатель не найден");
        }

        $message = new Messages();
        $message->setSender($sender);
        $message->setReceiver($receiver);
        $message->setContent($content);
        $this->entityManager->save($message);

        return true;
    }
}

