<?php


namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use App\Repository\UsersRepository;
use App\Service\SendCode;
use App\Service\TokenService;
use App\Service\RefreshTokenService;

class LoginSuccec implements AuthenticationSuccessHandlerInterface
{
    private SendCode $sendCode;
    private TokenService $token;
    private RefreshTokenService $generateRefreshTokenService;
    private  UsersRepository $usersRepository;


    public function __construct(

        UsersRepository $usersRepository,
        SendCode $sendCode,
        TokenService $token,
        RefreshTokenService $generateRefreshTokenService
    ) {
        $this->usersRepository = $usersRepository;
        $this->sendCode = $sendCode;
        $this->token = $token;
        $this->generateRefreshTokenService = $generateRefreshTokenService;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): JsonResponse
    {
        $user = $token->getUser();
        $email = $user->getUserIdentifier();


        $isVerified = $this->usersRepository->isVerified($email);


        if ($isVerified) {
            $AccToken = $this->token->createToken($user);
            $refToken = $this->generateRefreshTokenService->generateToken($user);
            $response = new JsonResponse( 'Пользователь успешно за логинился', 201);
            $response->headers->set('X-Acc-Token', $AccToken);
            $response->headers->set('X-Ref-Token', $refToken);
            return $response;
        }


        $AccToken = $this->token->createToken($user);
        $sendCode = $this->sendCode->send($email);

        if ($sendCode) {
            $response = new JsonResponse( 'Пожалуйста, подтвердите почту.', 201);
            $response->headers->set('X-Acc-Token', $AccToken);
            return $response;
        }

        return new JsonResponse([
            'message' => 'Произошла ошибка при отправке кода подтверждения.',
            'success' => false,
        ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}
