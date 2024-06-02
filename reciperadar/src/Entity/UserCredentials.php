<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\RecipeController;
use App\Controller\UserCredentialsController;
use App\Repository\UserCredentialsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserCredentialsRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new Post(),
        new Put()
    ],
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']]
)]
class UserCredentials
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['user:read'])]
    private $id;

    #[Assert\GreaterThanOrEqual(0)]
    #[ORM\Column(type: 'integer')]
    #[Groups(['user:read'])]
    private int $followersCount = 0;

    #[Assert\DateTime]
    #[ORM\Column(type: 'datetime')]
    #[Groups(['user:read'])]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: 'json')]
    #[Groups(['user:read'])]
    private array $followingUsers = [];

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $surname;

    #[ORM\ManyToMany(targetEntity: Recipe::class)]
    #[ORM\JoinTable(name: 'user_followed_recipes')]
    #[Groups(['user:read'])]
    private Collection $followedRecipes;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->followedRecipes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFollowersCount(): ?int
    {
        return $this->followersCount;
    }

    public function setFollowersCount(int $followersCount): self
    {
        $this->followersCount = $followersCount;
        return $this;
    }

    public function incrementFollowersCount(): self
    {
        $this->followersCount = $this->getFollowersCount() + 1;
        return $this;
    }

    public function decrementFollowersCount(): self
    {
        $this->followersCount = $this->getFollowersCount() - 1;
        return $this;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        if ($createdAt > new \DateTime()) {
            throw new \InvalidArgumentException("Creation date cannot be in the future.");
        }
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getFollowingUsers(): ?array
    {
        return $this->followingUsers;
    }

    public function setFollowingUsers(array $followingUsers): self
    {
        $this->followingUsers = $followingUsers;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * @return Collection|Recipe[]
     */
    public function getFollowedRecipes(): Collection
    {
        return $this->followedRecipes;
    }

    public function addFollowedRecipe(Recipe $recipe): self
    {
        if (!$this->followedRecipes->contains($recipe)) {
            $this->followedRecipes[] = $recipe;
        }
        return $this;
    }

    public function removeFollowedRecipe(Recipe $recipe): self
    {
        $this->followedRecipes->removeElement($recipe);
        return $this;
    }


}
