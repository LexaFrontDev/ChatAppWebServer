<?php


namespace App\Service\MessagesService;


use App\Entity\Messages;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Service\AuthService\TokenService;
use App\Command\Update\UpdateMessages\UpdateMessages;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Validation\MessagesValidate\ChangeMessagesValidator;

#[AsService]
class ChangeMessageService
{
    private ChangeMessagesValidator $messageValidator;
    private UpdateMessages $updateMessages;
    private TokenService $updateTokenService;
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct
    (
        ChangeMessagesValidator $messageValidator,
        UpdateMessages $updateMessages,
        TokenService $updateTokenService,
        EntityManagerInterface $entityManager,
        Security $security
    )
    {
        $this->messageValidator = $messageValidator;
        $this->updateMessages = $updateMessages;
        $this->updateTokenService = $updateTokenService;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }


    public function changeMessages($messagesId, $newMessages)
    {
        $user = $this->security->getUser();
        $message = $this->entityManager->getRepository(Messages::class)->find($messagesId);
        $this->messageValidator->validate($user, $newMessages, $message);

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