<?php


namespace App\Service\Change;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Service\TokenService;

class ChangeNameService
{
    private TokenService $updateTokenService;
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(
        TokenService $updateTokenService,
        EntityManagerInterface $entityManager,
        Security $security
    ) {
        $this->updateTokenService = $updateTokenService;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function changeNameService(string $newName): array
    {
        $user = $this->security->getUser();

        if (!$user instanceof Users) {
            throw new \RuntimeException("Пользователь не аутентифицирован");
        }

        $isChange = $this->entityManager->getRepository(Users::class)
            ->findOneBy(['email' => $user->getEmail()]);

        if (!$isChange) {
            throw new \InvalidArgumentException("Пользователь не найден");
        }

        $isChange->setName($newName);
        $this->entityManager->persist($isChange);
        $this->entityManager->flush();

        $accToken = $this->updateTokenService->createToken($user);

        return [
            'success' => true,
            'acc' => $accToken,
            'messages' => 'Имя успешно изменено',
        ];
    }
}
