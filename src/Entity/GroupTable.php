<?php


namespace App\Entity;


use App\Entity\Messages;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Repository\GroupTableRepository;

#[ORM\Entity]
#[ORM\InheritanceType("SINGLE_TABLE")]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
#[ORM\DiscriminatorMap(["subscriber" => Subscribers::class])]
#[ORM\Table(name: "group_table")]
abstract class GroupTable
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\Column(name: 'id_group', type: 'integer')]
    private ?int $id_group = null;

    #[ORM\Column(name: 'groupName', type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Имя группы не должно быть пустым")]
    #[Assert\Length(min: 2, max: 50, minMessage: "Имя группы должно содержать минимум {{ limit }} символа", maxMessage: "Имя группы не должно превышать {{ limit }} символов")]
    private ?string $nameGroup = null;

    #[ORM\Column(name: 'description', type: 'string', length: 400)]
    #[Assert\Length(min: 20, max: 100, minMessage: "Описание группы должно содержать минимум {{ limit }} символа", maxMessage: "Имя группы не должно превышать {{ limit }} символов")]
    private ?string $description = null;


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


    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }



}
