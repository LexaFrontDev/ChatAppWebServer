<?php


namespace App\Entity;

use App\Entity\Messages;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Repository\GroupTableRepository;

#[ORM\Entity]
class Subscribers extends GroupTable
{
    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\Column(name: 'id_users', type: 'integer')]
    private ?int $id_users = null;

    #[ORM\Column(name: 'name_users', type: 'string', length: 255, )]
    private ?string $nameUsers = null;

    #[ORM\Column(name: 'roles', type: "json", nullable: false)]
    #[Assert\NotBlank(message: "Роли обязательны")]
    private $roles = ['ROLE_SUBSCRIBER'];


    public function getId()
    {
        return $this->id;
    }


    public function getIdUsers()
    {
        return $this->id_users;
    }


    public function getNameUsers()
    {
        return $this->nameUsers;
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