<?php


namespace App\Controller\AuthController\ResetPasswordController;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Service\AuthService\VeryfiMailCode;
use App\Repository\UsersRepository;
use App\Command\Update\UpdatePassword\UpdatePasswordCommand;
use App\Command\Update\UpdateRoles\UpdateRolesCommand;


class ResetPasswordController extends AbstractController
{
    private UpdatePasswordCommand $updatePassword;
    private UsersRepository $userRepository;
    private VeryfiMailCode $veryfiMail;
    private UpdateRolesCommand $updateRole;


    public function __construct
    (
        UpdateRolesCommand $updateRole,
        UpdatePasswordCommand $updatePassword,
        UsersRepository $userRepository,
        VeryfiMailCode $veryfiMail
    )
    {
        $this->updateRole = $updateRole;
        $this->updatePassword = $updatePassword;
        $this->userRepository = $userRepository;
        $this->veryfiMail = $veryfiMail;
    }

    #[Route('/api/reset/password/reset', name: 'ResetPasswordResert', methods: ['POST'])]
    public function reset(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? '';
        $code = $data['code'] ?? '';
        $newPassword = $data['newPassword'] ?? '';

        try{
            $isVerifiedMail = $this->veryfiMail->veryfi($email, $code);

            if ($isVerifiedMail) {
                $setPassword = $this->updatePassword->updatePass($email, $newPassword);

                if ($setPassword) {
                    $this->updateRole->updateRoles($setPassword, ['ROLE_USER']);
                    return new JsonResponse('Пароль успешно изменён', 201);
                }
            } else {
                return new JsonResponse('Пользователь с такой почтой не существует', 404);
            }
        }catch (\InvalidArgumentException $e){
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

}