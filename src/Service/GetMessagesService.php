<?php


namespace App\Service;

use App\Entity\Users;
use Symfony\Bundle\SecurityBundle\Security;
use App\Query\Get\GetMessages\GetMessagesQuery;

#[AsService]
class GetMessagesService
{
    private Security $security;
    private GetMessagesQuery $getMessagesQuery;

    public function __construct(GetMessagesQuery $getMessagesQuery, Security $security)
    {
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
        return $result;
    }
}
