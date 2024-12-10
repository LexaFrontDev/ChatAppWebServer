<?php


namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class MessagesGroup extends Messages
{
    #[ORM\Column(name: "sender_id", type: Types::INTEGER)]
    private int $senderId;

    #[ORM\Column(name: "group_id", type: Types::INTEGER)]
    private int $groupId;

    public function getSenderId(): int
    {
        return $this->senderId;
    }

    public function setSenderId(int $senderId): void
    {
        $this->senderId = $senderId;
    }

    public function getGroupId(): int
    {
        return $this->groupId;
    }

    public function setGroupId(int $groupId): void
    {
        $this->groupId = $groupId;
    }
}