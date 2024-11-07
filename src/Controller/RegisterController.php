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
use App\Service\CheckUsersTable;
use App\Service\SendCode;


class RegisterController extends AbstractController
{
    private SendCode $sendCode;
    private EntityManagerInterface $entityManager;
    private CheckUsersTable $checkUsersTable;

    public function __construct(SendCode $sendCode,CheckUsersTable $checkUsersTable, EntityManagerInterface $entityManager)
    {
        $this->sendCode = $sendCode;
        $this->checkUsersTable = $checkUsersTable;
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

        $checkTable = $this->checkUsersTable->check($name, $email);

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

            $entityManager->persist($user);
            $entityManager->flush();



            $sendCode = $this->sendCode->send($email);

            return $this->json(['message' => 'Регистрация прошла успешно! пожалуйста подтвердите свою почту!'], Response::HTTP_CREATED);
        }

        return $this->json([
            'message' => 'Invalid form submission',
            'errors' => (string) $form->getErrors(true, false),
        ], Response::HTTP_BAD_REQUEST);
    }
}
