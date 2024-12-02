<?php


namespace App\Service;

use App\Entity\Users;
use App\Service\TokenService;
use Symfony\Bundle\SecurityBundle\Security;
use App\Query\Get\GetMessages\GetMessagesQuery;
use function Symfony\Config\em;


#[AsService]
class GetMessagesService
{
    private Security $security;
    private GetMessagesQuery $getMessagesQuery;
    private TokenService $accToken;

    public function __construct(TokenService $accToken, GetMessagesQuery $getMessagesQuery, Security $security)
    {
        $this->accToken = $accToken;
        $this->getMessagesQuery = $getMessagesQuery;
        $this->security = $security;
    }


    public function getAllMessages()
    {
        $receiver = $this->security->getUser();
        if (!$receiver instanceof Users) {
            throw new \RuntimeException("Пользователь не аутентифицирован");
        }

        $result = $this->getMessagesQuery->getMessages($receiver);
        $receivedMess = $result['receivedMessages'];
        $sentMess = $result['sentMessages'];
        $accToken = $this->accToken->createToken($receiver);

        if(empty($receivedMess) && !empty($sentMess)){
            return ['acc' => $accToken, 'date' => $result['sentMessages']];
        }
        if(!empty($receivedMess) && empty($sentMess)){
            return ['acc' => $accToken, 'date' => $result['receivedMessages']];
        }

        if(empty($receivedMess) || empty($sentMess)){
            return ['acc' => $accToken, 'date' => 'У вас нет активных чатов'];
        }

        return ['acc' => $accToken, 'date' => $result];;
    }
}
