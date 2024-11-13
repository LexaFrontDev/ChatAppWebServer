<?php


namespace App\Service;

use App\Entity\MailVeryfication;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsService;
use App\Service\TokenService;


#[AsService]
class VeryfiMailCode
{

    private $token;
    private EntityManagerInterface $entityManager;

    public function __construct(TokenService $token, EntityManagerInterface $entityManager)
    {
        $this->token = $token;
        $this->entityManager = $entityManager;
    }

    public function veryfi(string $email, string $code)
    {
        $this->entityManager->createQuery('DELETE FROM App\Entity\MailVeryfication m WHERE m.createdAt < :threshold')
            ->setParameter('threshold', (new \DateTime())->modify('-2 minutes'))
            ->execute();

        $repository = $this->entityManager->getRepository(MailVeryfication::class);
        $verification = $repository->findOneBy(['email' => $email, 'code' => $code]);



        if ($verification && (new \DateTime())->getTimestamp() - $verification->getCreatedAt()->getTimestamp() < 3600) {
            $repositoryUsers = $this->entityManager->getRepository(Users::class);
            $user = $repositoryUsers->findOneBy(['email' => $email]);
            if ($user) {
                $user->setVerified(true);
                $token = $this->token->createToken($user);
                $this->entityManager->flush();
                return ([
                    'acc' => $token,
                    'message' => 'Почта успешно верифицирована',
                    'success' => true,
                ]);
            }
        }

        throw new \InvalidArgumentException("Неверный код или код просрочен");
    }
}
