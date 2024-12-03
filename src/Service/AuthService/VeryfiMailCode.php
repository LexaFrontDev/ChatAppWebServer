<?php


namespace App\Service\AuthService;

use App\Entity\MailVeryfication;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\AuthService\TokenService;
use App\Service\AuthService\RefreshTokenService;

#[AsService]
class VeryfiMailCode
{
    private $generateRefreshTokenService;
    private $token;
    private EntityManagerInterface $entityManager;

    public function __construct(RefreshTokenService $generateRefreshTokenService, TokenService $token, EntityManagerInterface $entityManager)
    {
        $this->generateRefreshTokenService = $generateRefreshTokenService;
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
                $AccToken = $this->token->createToken($user);
                $this->entityManager->flush();
                return ([
                    'acc' => $AccToken,
                    'message' => 'Почта успешно верифицирована',
                ]);
            }
        }

        throw new \InvalidArgumentException("Неверный код или код просрочен");
    }
}
