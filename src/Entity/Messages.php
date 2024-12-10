<?php




namespace App\Entity;


use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\InheritanceType("SINGLE_TABLE")]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
#[ORM\DiscriminatorMap(["user" => MessagesUser::class, "group" => MessagesGroup::class])]
#[ORM\Table(name: "messages")]
abstract class Messages
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\Column(name: "id_message", type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(name: "content", type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(type: 'string', length: 4096, nullable: true)]
    private ?string $iv = null;

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


    public function getIv()
    {
        return $this->iv;
    }


    public function setIv($iv)
    {
        $this->iv = $iv;
    }
}
