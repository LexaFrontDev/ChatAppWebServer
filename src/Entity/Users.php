<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Messages;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



#[ORM\Entity]
#[ORM\Table(name: "Users")]
#[UniqueEntity(fields: ["name"], message: "Имя уже используется")]
#[UniqueEntity(fields: ["email"], message: "Email уже зарегистрирован")]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank(message: "Имя не должно быть пустым")]
    #[Assert\Length( min: 2, max: 50, minMessage: "Имя должно содержать минимум {{ limit }} символа", maxMessage: "Имя не должно превышать {{ limit }} символов")]
    private ?string $name = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank(message: "Email обязателен")]
    #[Assert\Email(message: "Неверный формат email")]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Пароль обязателен")]
    #[Assert\Length(min: 8, minMessage: "Пароль должен быть минимум {{ limit }} символов")]
    private ?string $password = null;

    #[ORM\Column(name: 'roles', type: "json", nullable: false)]
    #[Assert\NotBlank(message: "Роли обязательны")]
    private $roles = ['ROLE_USER'];

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $is_verified = false;


    #[ORM\OneToMany(mappedBy: "sender", targetEntity: Messages::class)]
    private Collection $sentMessages;

    #[ORM\OneToMany(mappedBy: "receiver", targetEntity: Messages::class)]
    private Collection $receivedMessages;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function isVerified(): bool
    {
        return $this->is_verified;
    }

    public function setVerified(bool $verified): self
    {
        $this->is_verified = $verified;
        return $this;
    }


    public function __construct()
    {
        $this->sentMessages = new ArrayCollection();
        $this->receivedMessages = new ArrayCollection();
    }

    public function getSentMessages(): Collection
    {
        return $this->sentMessages;
    }

    public function getReceivedMessages(): Collection
    {
        return $this->receivedMessages;
    }


}
