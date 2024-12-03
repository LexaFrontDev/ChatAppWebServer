<?php


namespace App\Service\Group;






use App\Entity\GroupTable;
use App\Entity\Subscribers;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Command\Follow\FollowGroupCommand;
use App\Service\TokenService;

#[AsService]
class FollowGroupService
{
    private $entityManager;
    private $followCommand;
    private $tokenService;

    public function __construct(TokenService $tokenService, FollowGroupCommand $followCommand, EntityManagerInterface $entityManager)
    {
        $this->tokenService = $tokenService;
        $this->followCommand = $followCommand;
        $this->entityManager = $entityManager;
    }


    public function followGroup(UserInterface $user, $id){
        $group = $this->entityManager->getRepository(GroupTable::class)->findOneBy(['id_group' => $id]);
        if (!$group) {throw new \Exception("Group not found");}
        $users = $this->entityManager->getRepository(Users::class)->findOneBy(['id' => $user->getId()]);
        if(!$user){throw new \Exception("Users not found");}
        $isSubscriber = $this->entityManager->getRepository(Subscribers::class)->findOneBy(['id_users' => $users->getId()]);
        if($isSubscriber){throw new \Exception("Users already subscribe");}
        $isFollowUser = $this->followCommand->followGroup($users, $group->getIdGroup());

        if($isFollowUser){
            $accToken = $this->tokenService->createToken($isFollowUser);
            return ['acc' => $accToken, 'messages' => 'Users successfully subscribe'];
        }

        throw new \Exception("failed follow");

    }

}