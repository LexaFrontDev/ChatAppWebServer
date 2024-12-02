<?php


namespace App\Controller\RegistrationController;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\SendCode;
use App\Service\TokenService;
use App\Service\RefreshTokenService;
use Symfony\Component\Form\FormFactoryInterface;


class RegisterController extends AbstractController
{
    private $generateRefreshTokenService;
    private $token;
    private SendCode $sendCode;
    private EntityManagerInterface $entityManager;
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory,RefreshTokenService $generateRefreshTokenService,TokenService $token,SendCode $sendCode, EntityManagerInterface $entityManager)
    {
        $this->formFactory = $formFactory;
        $this->generateRefreshTokenService = $generateRefreshTokenService;
        $this->token = $token;
        $this->sendCode = $sendCode;
        $this->entityManager = $entityManager;
    }

    #[Route('/api/register', name: 'Register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $user = new Users();


        $form = $this->formFactory->createNamed('', RegistrationFormType::class, $user);
        $formData = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ];

        $form->submit($formData);

        if ($form->isSubmitted() && $form->isValid()) {
            try{
                $user->setPassword(
                    $userPasswordHasher->hashPassword($user, $password)
                );

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $AccToken =  $this->token->createToken($user);
                $refToken = $this->generateRefreshTokenService->generateToken($user);
                $sendCode = $this->sendCode->send($email);


                $response = new JsonResponse('Регистрация прошла успешно! пожалуйста подтвердите свою почту!', 201);
                $response->headers->set('X-Acc-Token', $AccToken);
                $response->headers->set('X-Ref-Token', $refToken);
                return $response;
            }catch (\Exception $e){
                return new JsonResponse(['error' => 'Error: ' . $e->getMessage()], 400);
            }
        }

        return $this->json([
            'message' => 'Invalid form submission',
            'errors' => (string) $form->getErrors(true, false),
        ], Response::HTTP_BAD_REQUEST);
    }
}
