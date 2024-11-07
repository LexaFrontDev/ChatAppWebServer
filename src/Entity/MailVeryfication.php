<?php

namespace App\Entity;

use App\Repository\MailVeryficationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use DateTimeInterface;

#[ORM\Entity(repositoryClass: MailVeryficationRepository::class)]
#[Broadcast]
class MailVeryfication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_mail')]
    private ?int $id = null;

    #[ORM\Column(name: 'email', type: 'string', length: 255)]
    private string $email;

    #[ORM\Column(name: 'code', type: 'integer')]
    private int $code;

    #[ORM\Column(name: 'dateTime', type: "datetime")]
    private \DateTimeInterface $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
