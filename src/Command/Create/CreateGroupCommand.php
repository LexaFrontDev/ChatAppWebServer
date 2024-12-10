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
        $checkNameGroup = $this->entityManager->getRepository(Subscribers::class)->findOneBy(['nameGroup' => $nameGroup]);

        if($checkNameGroup){
            throw new \InvalidArgumentException('Имя группы занято');
        }

        if(!$user){
            throw new \InvalidArgumentException('Пользовател не существуеть');
        }

        $createGroup = new Subscribers();
        $createGroup->setNameGroup($nameGroup);
        $createGroup->setDescription($descriptionGroup);
        $createGroup->setIdUsers($userId);
        $createGroup->setNameUsers($user->getName());
        $createGroup->setRoles(['ROLE_CREATOR']);
        $this->entityManager->persist($createGroup);
        $this->entityManager->flush();
        return $user;

    }

}