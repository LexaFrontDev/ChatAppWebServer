<?php


namespace App\Command\Create;

use App\Entity\Users;
use App\Entity\GroupTable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class CreateGroupCommand
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function create($userId, $nameGroup){
        $user = $this->entityManager->getRepository(Users::class)
            ->findOneBy(['id' => $userId]);


        $createGroup = new GroupTable();
        $createGroup->setNameGroup($nameGroup);
        $createGroup->setSubscribers($user->getEmail());
        $createGroup->setRoles(['ROLE_CREATOR_GROUP']);
        $this->entityManager->persist($createGroup);
        $this->entityManager->flush();

        return $user;
    }

}