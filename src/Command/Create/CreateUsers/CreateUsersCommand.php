<?php


namespace App\Command\Create\CreateUsers;

use App\Repository\UsersRepository;
use App\Entity\Users;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUsersCommand
{

    private $userRepository;
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface  $passwordHasher, UsersRepository $userRepository)
    {
        $this->passwordHasher = $passwordHasher;
        $this->userRepository = $userRepository;
    }


    public function createUser($name, $email, $password)
    {
        $user = new Users();
        $user->setName($name);
        $user->getEmail($email);
        $hashedPassword = $this->passwordHasher->hashPassword($password);
        $user->setPassword($hashedPassword);
        $this->userRepository->save($user);
    }

}