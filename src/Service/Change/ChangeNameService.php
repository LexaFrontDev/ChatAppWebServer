<?php


namespace App\Service\Change;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Service\TokenService;
use App\Service\RefreshTokenService;
use App\Entity\RefreshToken;



#[AsService]
class ChangeNameService
{

    private TokenService $updateTokenService;
    private EntityManagerInterface $entityManager;
    private Security $security;
    private RefreshTokenService $generateRefToken;

    public function __construct(
        RefreshTokenService $generateRefToken,
        TokenService $updateTokenService,
        EntityManagerInterface $entityManager,
        Security $security
    ) {
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

        $deleteToken = $this->entityManager->getRepository(RefreshToken::class)
            ->findOneBy(['username' => $isChange->getName()]);

        if ($deleteToken) {
            $this->entityManager->remove($deleteToken);
            $this->entityManager->flush();
        }

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
