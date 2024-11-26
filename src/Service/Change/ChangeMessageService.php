<?php


namespace App\Service\Change;


use App\Entity\Messages;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Service\TokenService;
use App\Command\Update\UpdateMessages\UpdateMessages;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


#[AsService]
class ChangeMessageService
{
    private UpdateMessages $updateMessages;
    private TokenService $updateTokenService;
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct
    (
        UpdateMessages $updateMessages,
        TokenService $updateTokenService,
        EntityManagerInterface $entityManager,
        Security $security
    )
    {
        $this->updateMessages = $updateMessages;
        $this->updateTokenService = $updateTokenService;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }


    public function changeMessages($messagesId, $newMessages)
    {
        $user = $this->security->getUser();
        if (!$user instanceof Users) {
            throw new \RuntimeException("Пользователь не аутентифицирован");
        }
        if(!$newMessages){
            throw new \RuntimeException("Сообщение не должен быть пустым");
        }

        $message = $this->entityManager->getRepository(Messages::class)->find($messagesId);
        if (!$message) {
            throw new NotFoundHttpException("Сообщение не найдено");
        }
        if ($message->getSender()->getId() !== $user->getId()) {
            throw new \RuntimeException("У вас нет прав для изменения этого сообщения");
        }


        $isChange = $this->updateMessages->updateMess($messagesId, $newMessages);

        if($isChange){
            $accToken = $this->updateTokenService->createToken($user);
            return [
                'acc' => $accToken,
                'messages' => 'Сообщение успешно изменено',
            ];
        }
    }
}