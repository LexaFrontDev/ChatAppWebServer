<?php


namespace App\Service;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use App\Command\Create\CreateMessages\CreateMessagesCommand;
use App\Service\EncryptMessages\EncryptionService;



#[AsService]
class SendMessagesService
{
    private $encryptionService;
    private EntityManagerInterface $entityManager;
    private Security $security;
    private $messagesCommand;
    private $tokenService;

    public function __construct(
        EncryptionService $encryptionService,
        TokenService $tokenService,
        CreateMessagesCommand $messagesCommand,
        EntityManagerInterface $entityManager,
        Security $security
    ) {
        $this->encryptionService = $encryptionService;
        $this->tokenService = $tokenService;
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

        $encryptedData = $this->encryptionService->encryptMessage($content);

        $sendMessage = $this->messagesCommand->createMessages(
            $sender,
            $receiver,
            $encryptedData['encrypted_message'],
            $encryptedData['iv']
        );

        if ($sendMessage) {
            $accToken = $this->tokenService->createToken($sender);

            return [
                'succes' => true,
                'messages' => 'Пользователь успешно отправил сообщение',
            ];
        }
    }
}
