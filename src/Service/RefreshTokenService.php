<?php


namespace App\Service;

use App\Singleton\EntityManagerSingleton;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;



#[AsService]
class RefreshTokenService
{
    private RefreshTokenManagerInterface $refreshTokenManager;
    private EntityManagerSingleton $entityManager;

    public function __construct(RefreshTokenManagerInterface $refreshTokenManager, EntityManagerSingleton $entityManager)
    {
        $this->refreshTokenManager = $refreshTokenManager;
        $this->entityManager = $entityManager;
    }

    public function generateToken(UserInterface $user)
    {
        $refreshToken = $this->refreshTokenManager->create();
        $refreshToken->setUsername($user->getName());
        $refreshToken->setRefreshToken(bin2hex(random_bytes(32)));
        $refreshToken->setValid((new \DateTime())->modify('+1 month'));
        $this->entityManager->save($refreshToken);


        $token = $refreshToken->getRefreshToken();
        return $token;
    }
}
