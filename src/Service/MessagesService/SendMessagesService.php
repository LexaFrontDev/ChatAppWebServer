<?php


namespace App\Service\MessagesService;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use App\Command\Create\CreateMessages\CreateMessagesCommand;
use App\Service\MessagesService\EncryptionService;
use App\Service\AuthService\TokenService;

#[AsService]
class SendMessagesService
{
    private EncryptionService $encryptionService;
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
                'acc' => $accToken,
                'messages' => 'Пользователь успешно отправил сообщение',
            ];
        }
    }
}
