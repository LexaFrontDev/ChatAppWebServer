<?php


namespace App\Service;


use App\Singleton\EntityManagerSingleton;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Users;
use App\Service\SendCode;
use App\Service\TokenService;
use App\Facade\UserFacade;
use App\Service\RefreshTokenService;

#[AsService]
class LoginService
{
    private $usersFacade;
    private $generateRefreshTokenService;
    private SendCode $sendCode;
    private UserPasswordHasherInterface $hasher;
    private EntityManagerSingleton $entityManager;
    private TokenService $token;

    public function __construct(UserFacade $usersFacade,RefreshTokenService $generateRefreshTokenService,TokenService $token, SendCode $sendCode,  UserPasswordHasherInterface $hasher, EntityManagerSingleton $entityManager)
    {
        $this->usersFacade = $usersFacade;
        $this->generateRefreshTokenService = $generateRefreshTokenService;
        $this->token = $token;
        $this->sendCode = $sendCode;
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
            $isVerified = $this->usersFacade->isVerified($email);

            if ($isVerified) {
                $AccToken = $this->token->createToken($user);
                $refToken = $this->generateRefreshTokenService->generateToken($user);

                return [
                    'acc' => $AccToken,
                    'ref' => $refToken,
                    'message' => 'Пользователь успешно за логинилься',
                    'success' => true
                ];
            }

            $AccToken = $this->token->createToken($user);
            $sendCode = $this->sendCode->send($email);

            if($sendCode){
                return [
                    'acc' => $AccToken,
                    'result' => 'Пожалуйста, подтвердите почту.',
                    'success' => false
                ];
            }

        }
        throw new \InvalidArgumentException("Пользователь не найден");
    }
}
