<?php


namespace App\Command\Delete\DeleteRefreshTokens;


use App\Entity\RefreshToken;
use Doctrine\ORM\EntityManagerInterface;

class DeleteRefreshTokenCommand
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function deleteToken($userName)
    {
        $deleteToken = $this->entityManager->getRepository(RefreshToken::class)
            ->findOneBy(['username' => $userName]);

        if ($deleteToken) {
            $this->entityManager->remove($deleteToken);
            $this->entityManager->flush();
        }

        return true;
    }


}