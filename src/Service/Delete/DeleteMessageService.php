<?php


namespace App\Service\Delete;

use App\Entity\Messages;
use App\Entity\Users;
use Symfony\Bundle\SecurityBundle\Security;
use App\Command\Delete\DeleteMessages\DeleteMessageCommand;
use App\Service\TokenService;
use Doctrine\ORM\EntityManagerInterface;

#[AsService]
class DeleteMessageService
{
    private TokenService $tokenService;
    private DeleteMessageCommand $delete;
    private Security $security;
    private EntityManagerInterface $entityManager;

    public function __construct
    (
        TokenService $tokenService,
        DeleteMessageCommand $delete,
        Security $security,
         EntityManagerInterface $entityManager
    )
    {
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
        if (!$message) {
            throw new NotFoundHttpException("Сообщение не найдено");
        }
        if ($message->getSender()->getId() !== $user->getId()) {
            throw new \RuntimeException("У вас нет прав для удаление этого сообщения");
        }

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