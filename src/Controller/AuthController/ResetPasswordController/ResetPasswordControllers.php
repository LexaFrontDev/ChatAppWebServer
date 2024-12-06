<?php


namespace App\Controller\AuthController\ResetPasswordController;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UsersRepository;
use App\Service\AuthService\TokenService;
use App\Command\Update\UpdateRoles\UpdateRolesCommand;
use App\Service\AuthService\SendCode;
use App\Service\AuthService\VeryfiMailCode;
use App\Command\Update\UpdatePassword\UpdatePasswordCommand;


class ResetPasswordControllers extends AbstractController
{
    private  $userRepository;
    private  $tokenService;
    private  $updateRoles;
    private  $sendCode;
    private  $veryfiMail;
    private  $updatePassword;


    public function __construct
    (
        UpdatePasswordCommand $updatePassword,
        VeryfiMailCode $veryfiMail,
        SendCode $sendCode,
        UpdateRolesCommand $updateRoles,
        TokenService $tokenService,
        UsersRepository $userRepository
    )
    {
        $this->updatePassword = $updatePassword;
        $this->veryfiMail = $veryfiMail;
        $this->sendCode = $sendCode;
        $this->updateRoles = $updateRoles;
        $this->tokenService = $tokenService;
        $this->userRepository = $userRepository;
    }

    private function createJsonResponse($data, $status = 200, $headers = [])
    {
        $response = new JsonResponse($data, $status);
        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }
        return $response;
    }

    #[Route('/api/reset/password/send', name: 'ResetPasswordSend', methods: ['POST'])]
    public function resetPasswordSendCodeFun(Request $request){
        $date = json_decode($request->getContent(), true);
        $email = $date['email'] ?? '';
        $isUser = $this->userRepository->findOneByEmail($email);

        if (!$isUser) {
            return new JsonResponse('Пользователь с такой почтой не существует', 404);
        }

        $sendCode = $this->sendCode->send($email);
        if($sendCode){
            $accToken = $this->tokenService->createToken($isUser);
            return $this->createJsonResponse(['Код отправлен в почту пожалуйста подтвердите'], 200, ['X-Acc-Token' => $accToken]);
        }

        return new JsonResponse('Не получилось сбросить пароль', 400);

    }

    #[Route('/api/reset/password', name: 'ResetPasswordResert', methods: ['POST'])]
    public function resetPasswordCheckCodeFunction(Request $request){
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? '';
        $code = $data['code'] ?? '';
        $newPassword = $data['newPassword'] ?? '';


        try{
            $isVerifiedMail = $this->veryfiMail->veryfi($email, $code);

            if($isVerifiedMail){
                $setPassword = $this->updatePassword->updatePass($email, $newPassword);
                return $this->createJsonResponse(['Пароль успешно изменён'], 201, ['X-Acc-Token' => $isVerifiedMail['acc']]);
            }

            return new JsonResponse('Не получилось сбросить пароль', 400);
        }catch (\Exception $e){

        }
    }

}