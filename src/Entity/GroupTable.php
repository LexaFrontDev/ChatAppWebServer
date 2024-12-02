<?php


namespace App\Entity;


use App\Entity\Messages;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: GroupTableRepository::class)]
class GroupTable
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\Column(name: 'id_group', type: 'integer')]
    private ?int $id_group = null;

    #[ORM\Column(name: 'groupName', type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank(message: "Имя группы не должно быть пустым")]
    #[Assert\Length(min: 2, max: 50, minMessage: "Имя группы должно содержать минимум {{ limit }} символа", maxMessage: "Имя группы не должно превышать {{ limit }} символов")]
    private ?string $nameGroup = null;

    #[ORM\Column(name: 'subscribers', type: 'string', length: 255)]
    private ?string $subscribers = null;

    #[ORM\Column(name: 'rolesGroup', type: "json", nullable: false)]
    #[Assert\NotBlank(message: "Роли обязательны")]
    private $roles = ['ROLE_SUBSCRIBER'];

    #[ORM\Column(name: 'content', type: 'text', nullable: true)]
    private ?string $content = null;

    #[ORM\Column(name: 'iv', type: 'string', length: 255, nullable: true)]
    private ?string $iv = null;

    #[ORM\Column(name: 'sender', type: 'string', length: 255, nullable: true)]
    private ?string $sender = null;

    public function getIdGroup()
    {
        return $this->id_group;
    }


    public function getNameGroup()
    {
        return $this->nameGroup;
    }


    public function setNameGroup($nameGroup)
    {
        $this->nameGroup = $nameGroup;
    }


    public function getRoles()
    {
        return $this->roles;
    }


    public function setRoles($roles)
    {
        $this->roles = $roles;
    }


    public function getSubscribers()
    {
        return $this->subscribers;
    }

    public function setSubscribers($subscribers)
    {
        $this->subscribers = $subscribers;
    }



    public function getContent()
    {
        return $this->content;
    }


    public function setContent($content)
    {
        $this->content = $content;
    }


    public function getIv()
    {
        return $this->iv;
    }


    public function setIv($iv)
    {
        $this->iv = $iv;
    }


    public function getSender()
    {
        return $this->sender;
    }


    public function setSender($sender)
    {
        $this->sender = $sender;
    }
}
