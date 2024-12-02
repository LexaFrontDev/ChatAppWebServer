<?php


namespace App\Controller\ResetPasswordController;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UsersRepository;
use App\Service\SendCode;

class ResetPasswordSendController extends AbstractController
{

    private UsersRepository $userRepo;
    private SendCode $sendCode;

    public function __construct
    (
        SendCode $sendCode,
        UsersRepository $userRepo
    )
    {
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
            return new JsonResponse(
                [
                    'email' => $email,
                    'messages' => 'Код отправлен в почту подтвердите почту пожалуйста'
                ], 201);
        }else{
            new JsonResponse('Не получилось сбросить парол');
        }

    }
}