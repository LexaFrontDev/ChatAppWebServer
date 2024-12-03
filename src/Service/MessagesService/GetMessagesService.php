<?php


namespace App\Service\MessagesService;

use App\Entity\Users;
use App\Service\AuthService\TokenService;
use Symfony\Bundle\SecurityBundle\Security;
use App\Query\Get\GetMessages\GetMessagesQuery;
use App\Validation\MessagesValidate\GetMessagesValidator;


#[AsService]
class GetMessagesService
{
    private Security $security;
    private GetMessagesQuery $getMessagesQuery;
    private TokenService $accToken;
    private GetMessagesValidator $validator;

    public function __construct(GetMessagesValidator $validator, TokenService $accToken, GetMessagesQuery $getMessagesQuery, Security $security)
    {
        $this->validator = $validator;
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
        $validate = $this->validator->validate($receiver, $result);

        if ($validate){return $validate;}

        if (empty($validate)) {
            $accToken = $this->accToken->createToken($receiver);
            return ['acc' => $accToken, 'date' => $result];
        }
    }
}
