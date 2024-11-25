<?php


namespace App\Command\DeleteMessages;

use App\Entity\Messages;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;

class DeleteMessageCommand
{
    private EntityManagerInterface $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function deleteMessages($messagesId)
    {
        $message = $this->entityManager->getRepository(Messages::class)
            ->findOneBy(['id' => $messagesId]);

        if (!$message) {
            throw new NotFoundHttpException("Сообщение не найдено");
        }
        $this->entityManager->remove($message);
        $this->entityManager->flush();
        return true;
    }


}