<?php


namespace App\Service\AuthService;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Service\AuthService\TokenService;
use App\Service\AuthService\RefreshTokenService;
use App\Entity\RefreshToken;
use App\Command\Delete\DeleteRefreshTokens\DeleteRefreshTokenCommand;


#[AsService]
class ChangeNameService
{

    private TokenService $updateTokenService;
    private EntityManagerInterface $entityManager;
    private Security $security;
    private RefreshTokenService $generateRefToken;
    private DeleteRefreshTokenCommand $deleteRefreshTokens;

    public function __construct(
        DeleteRefreshTokenCommand $deleteRefreshTokens,
        RefreshTokenService $generateRefToken,
        TokenService $updateTokenService,
        EntityManagerInterface $entityManager,
        Security $security
    ) {
        $this->deleteRefreshTokens = $deleteRefreshTokens;
        $this->generateRefToken = $generateRefToken;
        $this->updateTokenService = $updateTokenService;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function changeNameService(string $newName): array
    {
        if (!$newName) {
            throw new \RuntimeException("Имя не должно быть пустым");
        }

        $user = $this->security->getUser();

        if (!$user instanceof Users) {
            throw new \RuntimeException("Пользователь не аутентифицирован");
        }


        $isChange = $this->entityManager->getRepository(Users::class)
            ->findOneBy(['email' => $user->getEmail()]);

        if (!$isChange) {
            throw new \InvalidArgumentException("Пользователь не найден");
        }

        $deleteOldToken = $this->deleteRefreshTokens->deleteToken($isChange->getName());

    if($deleteOldToken){
        $isChange->setName($newName);
        $this->entityManager->persist($isChange);
        $this->entityManager->flush();

        $refToken = $this->generateRefToken->generateToken($isChange);
        $accToken = $this->updateTokenService->createToken($isChange);

        return [
            'success' => true,
            'acc' => $accToken,
            'ref' => $refToken,
            'messages' => 'Имя успешно изменено',
        ];
    }
    }
}
