<?php


namespace App\Service\Group;






use App\Entity\GroupTable;
use App\Entity\Subscribers;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Command\Follow\FollowGroupCommand;
use App\Service\AuthService\TokenService;
use App\Validation\GroupValidation\FollowGroupValidator;

#[AsService]
class FollowGroupService
{
    private $entityManager;
    private $followCommand;
    private $tokenService;
    private $validator;

    public function __construct(FollowGroupValidator $validator,TokenService $tokenService, FollowGroupCommand $followCommand, EntityManagerInterface $entityManager)
    {
        $this->validator = $validator;
        $this->tokenService = $tokenService;
        $this->followCommand = $followCommand;
        $this->entityManager = $entityManager;
    }


    public function followGroup(UserInterface $user, $id){

        $isValidate = $this->validator->validate($user, $id);

        $users = $isValidate['users'];
        $group = $isValidate['group'];


        $isFollowUser = $this->followCommand->followGroup($users, $group->getIdGroup());

        if($isFollowUser){
            $accToken = $this->tokenService->createToken($isFollowUser);
            return ['acc' => $accToken, 'messages' => 'Users successfully subscribe'];
        }

        throw new \Exception("failed follow");

    }

}