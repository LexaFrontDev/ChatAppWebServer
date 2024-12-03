<?php


namespace App\Service\AuthService;

use Doctrine\ORM\EntityManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[AsService]
class RefreshTokenService
{
    private RefreshTokenGeneratorInterface $refreshTokenGenerator;
    private EntityManagerInterface $entityManager;

    public function __construct(RefreshTokenGeneratorInterface $refreshTokenGenerator, EntityManagerInterface $entityManager)
    {
        $this->refreshTokenGenerator = $refreshTokenGenerator;
        $this->entityManager = $entityManager;
    }

    public function generateToken(UserInterface $user)
    {

        $ttl = 30 * 24 * 60 * 60;
        $refreshToken = $this->refreshTokenGenerator->createForUserWithTtl(
            $user,
            $ttl
        );

        $this->entityManager->persist($refreshToken);
        $this->entityManager->flush();
        return $refreshToken->getRefreshToken();
    }
}
