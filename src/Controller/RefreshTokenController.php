<?php


namespace App\Controller;

use App\Entity\RefreshToken;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RefreshTokenController extends AbstractController
{
    private $entityManager;
    private JWTTokenManagerInterface $jwtManager;

    public function __construct(JWTTokenManagerInterface $jwtManager, EntityManagerInterface $entityManager)
    {
        $this->jwtManager = $jwtManager;
        $this->entityManager = $entityManager;
    }

    #[Route(path: '/api/token/refresh', name: 'refresh_token', methods: ['POST'])]
    public function refresh(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $refreshTokenString = $data['refresh_token'] ?? '';

        if (!$refreshTokenString) {
            return new JsonResponse(['error' => 'токен не передань'], 400);
        }

        $refreshToken = $this->entityManager->getRepository(RefreshToken::class)->findOneBy(['refreshToken' => $refreshTokenString]);

        if (!$refreshToken) {
            return new JsonResponse(['error' => 'токен не сущществуеть'], 400);
        }

        $username = $refreshToken->getUsername();
        if (!$username) {
            return new JsonResponse(['error' => 'Не нашли пользователя'], 404);
        }

        $user = $this->entityManager->getRepository(Users::class)->findOneBy(['name' => $username]);
        if (!$user) {
            return new JsonResponse(['error' => 'Пользователь не найден'], 404);
        }

        $token = $this->jwtManager->create($user);
        return new JsonResponse(['acc' => $token], 201);
    }
}