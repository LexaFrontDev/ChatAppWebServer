<?php


namespace App\Validation\MessagesValidate;

use App\Entity\Users;
use App\Entity\Messages;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteMessagesValidator
{

    public function validate($user ,$message){
        if (!$message) {
            throw new NotFoundHttpException("Сообщение не найдено");
        }
        if ($message->getSender()->getId() !== $user->getId()) {
            throw new \RuntimeException("У вас нет прав для удаление этого сообщения");
        }
    }

}