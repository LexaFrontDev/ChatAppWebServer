<?php


namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Users;
use App\Service\SendCode;
use App\Service\GetVeryfed;



#[AsService]
class LoginService
{
    private SendCode $sendCode;
    private GetVeryfed $getVeryfed;
    private UserPasswordHasherInterface $hasher;
    private EntityManagerInterface $entityManager;

    public function __construct(SendCode $sendCode, GetVeryfed $getVeryfed, UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager)
    {
        $this->sendCode = $sendCode;
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


            $sendCode = $this->sendCode->send($email);

            if($sendCode){
                return [
                    'result' => 'Пожалуйста, подтвердите почту.',
                    'success' => false
                ];
            }

        }
        throw new \InvalidArgumentException("Пользователь не найден");
    }
}
