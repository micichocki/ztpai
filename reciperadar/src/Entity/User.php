<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\UserController;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    operations: [
        new Get(),
        new Post(),
        new Patch(
            denormalizationContext: [
                'groups' => ['user:write:patch']
            ]
        ),
        new Delete(
            uriTemplate: 'user/{user_id}',
            controller: UserController::class
        )
    ],
    normalizationContext: [
        'groups' => ['user:read'],
    ],
    denormalizationContext: [
        'groups' => ['user:write'],
    ]
)]
#[GetCollection(
    uriTemplate: 'users',
    controller: UserController::class,
    normalizationContext: [
        'groups' => ['user:read']
    ]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read','recipe:read'])]
    private ?int $id = null;

    #[Assert\Email]
    #[Assert\NotBlank(groups: ['user:write'])]
    #[Groups(['user:read', 'user:write','recipe:read'])]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;


    #[Groups(['user:read'])]
    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    #[Groups(['user:read', 'user:write', 'user:write:patch'])]
    private ?string $password = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Assert\Valid]
    #[Groups(['user:read', 'user:write'])]
    private ?UserCredentials $userCredentials = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }



    public function getUserCredentials(): ?UserCredentials
    {
        return $this->userCredentials;
    }

    public function setUserCredentials(?UserCredentials $userCredentials): self
    {
        $this->userCredentials = $userCredentials;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }
}
