<?php


namespace App\Controller\ResetPasswordController;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UsersRepository;
use App\Service\SendCode;
use App\Service\TokenService;


class ResetPasswordSendController extends AbstractController
{

    private UsersRepository $userRepo;
    private SendCode $sendCode;
    private TokenService $tokenService;


    public function __construct(TokenService $tokenService ,SendCode $sendCode, UsersRepository $userRepo)
    {
        $this->tokenService = $tokenService;
        $this->sendCode = $sendCode;
        $this->userRepo = $userRepo;
    }

    #[Route('/api/reset/password/send', name: 'ResetPasswordSend', methods: ['POST'])]
    public function resetPassSend(Request $request)
    {
        $date = json_decode($request->getContent(), true);
        $email = $date['email'] ?? '';
        $isUser = $this->userRepo->findOneByEmail($email);

        if (!$isUser) {
            return new JsonResponse('Пользователь с такой почтой не существует', 404);
        }

        $sendCode = $this->sendCode->send($email);
        if($sendCode)
        {
            $payload = ['roles' => 'ROLE_SENT'];
            $accToken = $this->tokenService->createToken($isUser, $payload);

            $response = new JsonResponse('Код отправлен в почту пожалуйста подтвердите', 200);
            $response->headers->set('X-Acc-Token', $accToken);
            $response->setData(['email' => $email]);
            return $response;
        }else{
            new JsonResponse('Не получилось сбросить парол');
        }

    }
}