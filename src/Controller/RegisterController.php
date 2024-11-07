<?php


namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class RegisterController extends AbstractController
{
    private $emailVerifier;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, EmailVerifier $emailVerifier)
    {
        $this->entityManager = $entityManager;
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/api/register', name: 'Register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'] ?? '';
        $email = $data['email'] ?? '';
        $plainPassword = $data['plainPassword'] ?? '';

        $user = new Users();

        $userByName = $this->entityManager->getRepository(Users::class)->findOneBy(['name' => $name]);
        $userByEmail = $this->entityManager->getRepository(Users::class)->findOneBy(['email' => $email]);

        if ($userByName || $userByEmail) {
            return new JsonResponse('Имя или почта пользователя уже есть', 400);
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

            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('LexaDev@example.com', 'AcmeMailBot'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
            );

            return $this->json(['message' => 'Registration successful!'], Response::HTTP_CREATED);
        }

        return $this->json([
            'message' => 'Invalid form submission',
            'errors' => (string) $form->getErrors(true, false),
        ], Response::HTTP_BAD_REQUEST);
    }
}
