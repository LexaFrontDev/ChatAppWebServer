<?php


namespace App\Service;


use App\Entity\Users;
use App\Singleton\EntityManagerSingleton;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use App\Facade\MessagesFacade;

#[AsService]
class SendMessagesService
{
    private EntityManagerSingleton $entityManager;
    private Security $security;
    private $messagesFacade;

    public function __construct(MessagesFacade $messagesFacade, EntityManagerSingleton $entityManager, Security $security)
    {
        $this->messagesFacade = $messagesFacade;
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

        $this->messagesFacade->createMessages($sender, $receiver, $content);


        return true;
    }
}

