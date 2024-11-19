<?php


namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Facade\UserFacade;
use App\Service\SendCode;
use App\Service\TokenService;
use App\Service\RefreshTokenService;


class RegisterController extends AbstractController
{
    private $generateRefreshTokenService;
    private $token;
    private SendCode $sendCode;
    private EntityManagerInterface $entityManager;
    private $usersFacade;

    public function __construct(UserFacade $usersFacade,RefreshTokenService $generateRefreshTokenService,TokenService $token,SendCode $sendCode, EntityManagerInterface $entityManager)
    {
        $this->generateRefreshTokenService = $generateRefreshTokenService;
        $this->token = $token;
        $this->sendCode = $sendCode;
        $this->usersFacade = $usersFacade;
        $this->entityManager = $entityManager;
    }

    #[Route('/api/register', name: 'Register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'] ?? '';
        $email = $data['email'] ?? '';
        $plainPassword = $data['plainPassword'] ?? '';

        $user = new Users();

        $checkTable = $this->usersFacade->isUserUnique($name, $email);

        if (!$checkTable) {
            return new JsonResponse('Пользователь с таким именем или почтой существуеть', 400);
        }

        $form = $this->createForm(RegistrationFormType::class, $user, [
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ]);

        $formData = [
            'name' => $name,
            'email' => $email,
            'plainPassword' => $plainPassword,
        ];

        $form->submit($formData);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $data['plainPassword']
                )
            );


            $setRole = $user->setRoles(['ROLE_USER']);
            $entityManager->persist($user, $setRole);
            $entityManager->flush();


            $AccToken =  $this->token->createToken($user);
            $refToken = $this->generateRefreshTokenService->generateToken($user);
            $sendCode = $this->sendCode->send($email);



            return $this->json([
                'acc' => $AccToken,
                'ref' => $refToken,
                'message' => 'Регистрация прошла успешно! пожалуйста подтвердите свою почту!'
            ], Response::HTTP_CREATED);
        }

        return $this->json([
            'message' => 'Invalid form submission',
            'errors' => (string) $form->getErrors(true, false),
        ], Response::HTTP_BAD_REQUEST);
    }
}
