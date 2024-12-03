<?php


namespace App\Command\Follow;

use App\Entity\Subscribers;
use App\Entity\Users;
use App\Entity\GroupTable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class FollowGroupCommand
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function followGroup(UserInterface $user, $id)
    {
            $subscribe = new Subscribers();
            $subscribe->setIdGroup($id);
            $subscribe->setIdUsers($user->getId());
            $subscribe->setNameUsers($user->getName());
            $this->entityManager->persist($subscribe);
            $this->entityManager->flush();
            return $user;
    }

}