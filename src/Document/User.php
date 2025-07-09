<?php

namespace App\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[MongoDB\Document]
class User implements UserInterface, PasswordAuthenticatedUserInterface{
    #[MongoDB\Id]
    private ?string $id = null;

    #[MongoDB\Field(type: "string")]
    #[Assert\NotBlank(message: "Digite seu nome completo")]
    #[Assert\Regex(
        pattern: "/^\s*\S+\s+\S+.*$/",
        message: "Digite seu nome completo (nome e sobrenome)"
    )]
    private string $name;

    #[MongoDB\Field(type: "string")]
    #[Assert\Email(message: "Email inválido")]
    #[Assert\NotBlank(message: "Digite seu email")]
    private string $email;

    #[MongoDB\Field(type: "string")]
    #[Assert\NotBlank(message: "Digite sua senha")]
    #[Assert\Length(
        min: 6,
        minMessage: 'A senha deve ter pelo menos {{ limit }} caracteres.'
    )]
    private string $password;

    // Getters e setters
    public function getId(): ?string { return $this->id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = $email; }

    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): void { $this->password = $password; }

    public function getUserIdentifier(): string { return $this->email; }
    public function getRoles(): array { return ['ROLE_USER']; }
    public function eraseCredentials(): void {}
}
