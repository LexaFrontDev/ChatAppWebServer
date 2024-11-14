<?php




namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "Messages")]
class Messages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_message", type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: "sentMessages")]
    #[ORM\JoinColumn(name: "sender_id", referencedColumnName: "id", nullable: false)]
    private ?Users $sender = null;

    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: "receivedMessages")]
    #[ORM\JoinColumn(name: "receiver_id", referencedColumnName: "id", nullable: false)]
    private ?Users $receiver = null;

    #[ORM\Column(name: "content", type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(name: "created_at", type: Types::DATETIME_MUTABLE)]
    private \DateTime $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSender(): ?Users
    {
        return $this->sender;
    }

    public function setSender(Users $sender): self
    {
        $this->sender = $sender;
        return $this;
    }

    public function getReceiver(): ?Users
    {
        return $this->receiver;
    }

    public function setReceiver(Users $receiver): self
    {
        $this->receiver = $receiver;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }



}
