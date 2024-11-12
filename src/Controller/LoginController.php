<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\LoginService;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{

    private LoginService $LoginService;

    public function __construct(LoginService $LoginService)
    {
        $this->LoginService = $LoginService;
    }

    #[Route('/api/login', name: 'Login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $name = $data['name'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        try {
            $login = $this->LoginService->loginService($name, $email, $password);
            return new JsonResponse($login, 201);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => 'Error: ' . $e->getMessage()], 400);
        }
    }
}
