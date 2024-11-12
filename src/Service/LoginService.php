<?php


namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Users;
use App\Service\GetVeryfed;

#[AsService]
class LoginService
{
    private GetVeryfed $getVeryfed;
    private UserPasswordHasherInterface $hasher;
    private EntityManagerInterface $entityManager;

    public function __construct(GetVeryfed $getVeryfed, UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager)
    {
        $this->getVeryfed =  $getVeryfed;
        $this->entityManager = $entityManager;
        $this->hasher = $hasher;
    }

    public function loginService($name, $email, $password)
    {
        if (empty($name)) {
            throw new \InvalidArgumentException("Не указано имя пользователя");
        }

        if (empty($email)) {
            throw new \InvalidArgumentException("Не указана почта пользователя");
        }

        if (empty($password)) {
            throw new \InvalidArgumentException("Не указан пароль пользователя");
        }


        $user = $this->entityManager->getRepository(Users::class)->findOneBy([
            'name' => $name,
            'email' => $email,
        ]);


        if ($user) {
            if (!$this->hasher->isPasswordValid($user, $password)) {
                throw new \InvalidArgumentException("Неверный пароль");
            }
            $veryfed = $this->getVeryfed->getVeryFed($email);

            if ($veryfed) {
                return [
                    'message' => 'Пользователь успешно за логинилься',
                    'success' => true
                ];
            }

            return [
                'result' => 'Пожалуйста, подтвердите почту.',
                'success' => false
            ];
        }
        throw new \InvalidArgumentException("Пользователь не найден");
    }
}
