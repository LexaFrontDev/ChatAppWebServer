<?php


namespace App\Entity;

use App\Entity\Messages;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Repository\GroupTableRepository;

#[ORM\Entity]
#[ORM\Table(name: "Subscribers")]
class Subscribers
{

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\Column(name: 'id', type: 'integer')]
    private ?int $id = null;

    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\Column(name: 'idGroup', type: 'integer')]
    private ?int $id_group = null;

    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\Column(name: 'idUsers', type: 'integer')]
    private ?int $id_users = null;

    #[ORM\Column(name: 'groupName', type: 'string', length: 255, unique: true)]
    private ?string $nameUsers = null;

    #[ORM\Column(name: 'roles', type: "json", nullable: false)]
    #[Assert\NotBlank(message: "Роли обязательны")]
    private $roles = ['ROLE_SUBSCRIBER'];


    public function getId()
    {
        return $this->id;
    }


    public function getIdGroup()
    {
        return $this->id_group;
    }

    public function getIdUsers()
    {
        return $this->id_users;
    }


    public function getNameUsers()
    {
        return $this->nameUsers;
    }


    public function setIdGroup($id_group)
    {
        $this->id_group = $id_group;
    }


    public function setIdUsers($id_users)
    {
        $this->id_users = $id_users;
    }


    public function setNameUsers($nameUsers)
    {
        $this->nameUsers = $nameUsers;
    }


    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }
}