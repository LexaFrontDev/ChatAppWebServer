<?php


namespace App\Command\Create;

use App\Entity\Users;
use App\Entity\Subscribers;
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


    public function create($userId, $nameGroup, $descriptionGroup){
        $user = $this->entityManager->getRepository(Users::class)->findOneBy(['id' => $userId]);

        if(!$user){
            return false;
        }

        $createGroup = new GroupTable();
        $createGroup->setNameGroup($nameGroup);
        $createGroup->setDescription($descriptionGroup);
        $this->entityManager->persist($createGroup);
        $this->entityManager->flush();

        $IsIdGroup = $this->entityManager->getRepository(GroupTable::class)->findOneBy(['nameGroup' => $nameGroup]);

        if($IsIdGroup){
            $createCreator = new Subscribers();
            $createCreator->setIdGroup($IsIdGroup->getIdGroup());
            $createCreator->setIdUsers($user->getId());
            $createCreator->setNameUsers($user->getName());
            $createCreator->setRoles(['ROLE_CREATOR_GROUP']);
            $this->entityManager->persist($createCreator);
            $this->entityManager->flush();
            return $user;
        }
    }

}