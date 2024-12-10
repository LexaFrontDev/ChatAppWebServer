<?php


namespace App\Service\MessagesService;

use App\Entity\Users;
use App\Service\AuthService\TokenService;
use Symfony\Bundle\SecurityBundle\Security;
use App\Query\Get\GetMessages\GetMessagesQuery;
use App\Validation\MessagesValidate\GetMessagesValidator;
use App\Service\UsersService\GetUserInSecurityService;

#[AsService]
class GetMessagesService
{
    private Security $security;
    private GetMessagesQuery $getMessagesQuery;
    private TokenService $accToken;
    private GetMessagesValidator $validator;
    private GetUserInSecurityService $getUserService;

    public function __construct(GetUserInSecurityService $getUserService, GetMessagesValidator $validator, TokenService $accToken, GetMessagesQuery $getMessagesQuery, Security $security)
    {
        $this->getUserService = $getUserService;
        $this->validator = $validator;
        $this->accToken = $accToken;
        $this->getMessagesQuery = $getMessagesQuery;
        $this->security = $security;
    }


    public function getAllMessages()
    {
        $receiver = $this->getUserService->getSender();

        $result = $this->getMessagesQuery->getMessages($receiver);
        $validate = $this->validator->validate($receiver, $result);

        if ($validate){return $validate;}

        if (empty($validate)) {
            $accToken = $this->accToken->createToken($receiver);
            return ['acc' => $accToken, 'date' => $result];
        }
    }
}
