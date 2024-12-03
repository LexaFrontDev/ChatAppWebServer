<?php


namespace App\Controller\AuthController\ResetPasswordController;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UsersRepository;
use App\Service\SendCode;
use App\Service\TokenService;
use Doctrine\ORM\EntityManagerInterface;
use App\Command\Update\UpdateRoles\UpdateRolesCommand;

class ResetPasswordSendController extends AbstractController
{
    private UsersRepository $userRepo;
    private SendCode $sendCode;
    private TokenService $tokenService;
    private EntityManagerInterface $entityManager;
    private UpdateRolesCommand $updateRoles;


    public function __construct(UpdateRolesCommand $updateRoles,TokenService $tokenService, SendCode $sendCode, UsersRepository $userRepo, EntityManagerInterface $entityManager)
    {
        $this->updateRoles = $updateRoles;
        $this->tokenService = $tokenService;
        $this->sendCode = $sendCode;
        $this->userRepo = $userRepo;
        $this->entityManager = $entityManager;
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
        if ($sendCode) {
            $isRoles = $this->updateRoles->updateRoles($isUser, ['ROLE_SENT']);

            if($isRoles) {
                $accToken = $this->tokenService->createToken($isRoles);
                $response = new JsonResponse('Код отправлен в почту пожалуйста подтвердите', 200);
                $response->headers->set('X-Acc-Token', $accToken);
                $response->setData(['email' => $email]);
                return $response;
            }else {
                return new JsonResponse('не удалось установить роли', 400);
            }
        } else {
            return new JsonResponse('Не получилось сбросить пароль', 500);
        }
    }
}
