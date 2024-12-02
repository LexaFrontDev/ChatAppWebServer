<?php


namespace App\Command\Update\UpdateRoles;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;


class UpdateRolesCommand
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function updateRoles(UserInterface $user, array $roles)
    {
        $user->setRoles($roles);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

}