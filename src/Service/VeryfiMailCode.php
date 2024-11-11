<?php


namespace App\Service;

use App\Entity\MailVeryfication;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsService;

#[AsService]
class VeryfiMailCode
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function veryfi(string $email, string $code): bool
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
                $user->setIsVerified(true);
                $this->entityManager->flush();
                return true;
            }
        }

        throw new \InvalidArgumentException("Неверный код или код просрочен");
    }
}
