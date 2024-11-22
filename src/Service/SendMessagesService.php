<?php


namespace App\Service;


use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use App\Command\CreateMessages\CreateMessagesCommand;

#[AsService]
class SendMessagesService
{
    private EntityManagerInterface $entityManager;
    private Security $security;
    private $messagesCommand;

    public function __construct(CreateMessagesCommand $messagesCommand, EntityManagerInterface $entityManager, Security $security)
    {
        $this->messagesCommand = $messagesCommand;
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

        $this->messagesCommand->createMessages($sender, $receiver, $content);


        return true;
    }
}

