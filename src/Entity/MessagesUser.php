<?php


namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class MessagesUser extends Messages
{
    #[ORM\Column(name: "sender_id", type: Types::INTEGER)]
    private int $senderId;

    #[ORM\Column(name: "receiver_id", type: Types::INTEGER)]
    private int $receiverId;

    public function getSenderId(): int
    {
        return $this->senderId;
    }

    public function setSenderId(int $senderId): void
    {
        $this->senderId = $senderId;
    }

    public function getReceiverId(): int
    {
        return $this->receiverId;
    }

    public function setReceiverId(int $receiverId): void
    {
        $this->receiverId = $receiverId;
    }

}