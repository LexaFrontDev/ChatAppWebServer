<?php


namespace App\Service\MessagesService;

use App\Entity\GroupTable;
use App\Entity\Subscribers;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use App\Command\Create\CreateMessages\CreateMessagesUsersCommand;
use App\Command\Create\CreateMessages\CreateMessagesGroupCommand;
use App\Service\MessagesService\EncryptionService;
use App\Service\AuthService\TokenService;
use App\Service\UsersService\GetUserInSecurityService;


#[AsService]
class SendMessagesService
{
    private EncryptionService $encryptionService;
    private EntityManagerInterface $entityManager;
    private Security $security;
    private $getUsersInSecurity;
    private $messagesUsersCommand;
    private $tokenService;
    private $messagesGroupCommand;

    public function __construct(
        GetUserInSecurityService $getUsersInSecurity,
        EncryptionService $encryptionService,
        TokenService $tokenService,
        CreateMessagesUsersCommand $messagesUsersCommand,
        CreateMessagesGroupCommand $messagesGroupCommand,
        EntityManagerInterface $entityManager,
        Security $security
    ) {
       $this->getUsersInSecurity = $getUsersInSecurity;
        $this->messagesGroupCommand = $messagesGroupCommand;
        $this->encryptionService = $encryptionService;
        $this->tokenService = $tokenService;
        $this->messagesUsersCommand = $messagesUsersCommand;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }



    public function sendMessagesUsers(int $receiverId, string $content)
    {
        $sender = $this->getUsersInSecurity->getSender();
        $senderId = $sender->getId();

        $receiver = $this->entityManager->getRepository(Users::class)->find($receiverId);

        if (!$receiver) {
            throw new \InvalidArgumentException("Получатель не найден");
        }

        $encryptedData = $this->encryptionService->encryptMessage($content);
        $sendMessage = $this->messagesUsersCommand->createMessages(
            $senderId,
            $receiverId,
            $encryptedData['encrypted_message'],
            $encryptedData['iv']
        );

        if ($sendMessage) {
            $accToken = $this->tokenService->createToken($sender);

            return ['acc' => $accToken, 'messages' => 'Пользователь успешно отправил сообщение',];
        }
    }

    public function sendMessagesGroup(int $groupId, string $content){
        $sender = $this->getUsersInSecurity->getSender();
        $senderId = $sender->getId();


        $group = $this->entityManager->getRepository(GroupTable::class)->find($groupId);

        if (!$group) {
            throw new \InvalidArgumentException("Группа  не найдена");
        }

        $checkSender = $group->getIdUsers();

        if(!$checkSender){
            throw new \InvalidArgumentException("Пользовател не подписан в группу");
        }

        $encryptedData = $this->encryptionService->encryptMessage($content);
        $sendMessage = $this->messagesGroupCommand->createMessages(
            $senderId,
            $groupId,
            $encryptedData['encrypted_message'],
            $encryptedData['iv']
        );

        if ($sendMessage) {
            $accToken = $this->tokenService->createToken($sender);

            return ['acc' => $accToken, 'messages' => 'Пользователь успешно отправил сообщение',];
        }
    }


}
