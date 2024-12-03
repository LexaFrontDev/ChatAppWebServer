<?php


namespace App\Service\MessagesService;

use App\Entity\Messages;
use App\Entity\Users;
use Symfony\Bundle\SecurityBundle\Security;
use App\Command\Delete\DeleteMessages\DeleteMessageCommand;
use App\Service\AuthService\TokenService;
use Doctrine\ORM\EntityManagerInterface;
use App\Validation\MessagesValidate\DeleteMessagesValidator;

#[AsService]
class DeleteMessageService
{
    private TokenService $tokenService;
    private DeleteMessageCommand $delete;
    private DeleteMessagesValidator $validator;
    private Security $security;
    private EntityManagerInterface $entityManager;

    public function __construct
    (
        DeleteMessagesValidator $validator,
        TokenService $tokenService,
        DeleteMessageCommand $delete,
        Security $security,
        EntityManagerInterface $entityManager
    )
    {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
        $this->tokenService = $tokenService;
        $this->delete = $delete;
        $this->security = $security;
    }

    public function delete($messagesId)
    {
        $user = $this->security->getUser();

        if (!$user instanceof Users) {
            throw new \RuntimeException("Пользователь не аутентифицирован");
        }

        $message = $this->entityManager->getRepository(Messages::class)->find($messagesId);
        $isValidate = $this->validator->validate($user, $message);
        $IsDelete = $this->delete->deleteMessages($messagesId);

        if($IsDelete){
            $accToken = $this->tokenService->createToken($user);

            return [
                'acc' => $accToken,
                'message' => 'Сообщение успешно удален',
            ];
        }
    }
}