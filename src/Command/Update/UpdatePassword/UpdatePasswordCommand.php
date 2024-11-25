<?php


namespace App\Command\Update\UpdatePassword;

use App\Repository\UsersRepository;
use App\Entity\Users;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdatePasswordCommand
{
    private UsersRepository $userRepo;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        UsersRepository $userRepo
    ) {
        $this->passwordHasher = $passwordHasher;
        $this->userRepo = $userRepo;
    }

    public function updatePass($email, $newPass)
    {
        $findUser = $this->userRepo->findOneByEmail($email);
        if (!$newPass) {
            throw new \InvalidArgumentException('Заполните поле пароля, пожалуйста');
        }
        $hashPassword = $this->passwordHasher->hashPassword($findUser, $newPass);
        $findUser->setPassword($hashPassword);
        $this->userRepo->save($findUser);
        return $findUser;
    }
}
