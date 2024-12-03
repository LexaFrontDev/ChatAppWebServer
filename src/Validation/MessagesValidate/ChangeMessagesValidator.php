<?php


namespace App\Validation\MessagesValidate;

use App\Entity\Users;
use App\Entity\Messages;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ChangeMessagesValidator
{
    public function validate(Users $user, ?string $newMessages, ?Messages $message): void
    {
        if (!$user instanceof Users) {
            throw new \RuntimeException("Пользователь не аутентифицирован");
        }

        if (empty($newMessages)) {
            throw new \RuntimeException("Сообщение не должно быть пустым");
        }

        if (!$message) {
            throw new NotFoundHttpException("Сообщение не найдено");
        }

        if ($message->getSender()->getId() !== $user->getId()) {
            throw new \RuntimeException("У вас нет прав для изменения этого сообщения");
        }
    }
}