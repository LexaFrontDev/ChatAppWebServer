<?php


namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use App\Facade\UserFacade;
use App\Service\SendCode;
use App\Service\TokenService;
use App\Service\RefreshTokenService;

class LoginSuccec implements AuthenticationSuccessHandlerInterface
{
    private UserFacade $userFacade;
    private SendCode $sendCode;
    private TokenService $token;
    private RefreshTokenService $generateRefreshTokenService;

    public function __construct(
        SendCode $sendCode,
        UserFacade $userFacade,
        TokenService $token,
        RefreshTokenService $generateRefreshTokenService
    ) {
        $this->sendCode = $sendCode;
        $this->userFacade = $userFacade;
        $this->token = $token;
        $this->generateRefreshTokenService = $generateRefreshTokenService;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): JsonResponse
    {
        $user = $token->getUser();
        $email = $user->getUserIdentifier();


        $isVerified = $this->userFacade->isVerified($email);

        if ($isVerified) {
            $AccToken = $this->token->createToken($user);
            $refToken = $this->generateRefreshTokenService->generateToken($user);

            return new JsonResponse([
                'acc' => $AccToken,
                'ref' => $refToken,
                'message' => 'Пользователь успешно за логинился',
                'success' => true,
            ]);
        }


        $AccToken = $this->token->createToken($user);
        $sendCode = $this->sendCode->send($email);

        if ($sendCode) {
            return new JsonResponse([
                'acc' => $AccToken,
                'result' => 'Пожалуйста, подтвердите почту.',
                'success' => false,
            ]);
        }

        return new JsonResponse([
            'message' => 'Произошла ошибка при отправке кода подтверждения.',
            'success' => false,
        ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}
