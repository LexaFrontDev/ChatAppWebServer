<?php


namespace App\Service\UsersService;


use App\Entity\Users;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Bundle\SecurityBundle\Security;

class GetUserInSecurityService
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getSender(){
        $sender = $this->security->getUser();

        if (!$sender instanceof Users) {
            throw new UnauthorizedHttpException('Bearer', 'Отправитель не авторизован');
        }

        return $sender;
    }

}