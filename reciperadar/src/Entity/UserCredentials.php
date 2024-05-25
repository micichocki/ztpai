<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserCredentialsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserCredentialsRepository::class)]
#[ApiResource]
class UserCredentials
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Assert\GreaterThanOrEqual(0)]
    #[ORM\Column(type: 'integer')]
    private int $followersCount = 0;

    #[Assert\DateTime]
    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: 'json')]
    private $followingUsers = [];


    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'userCredentials', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\ManyToMany(targetEntity: Recipe::class)]
    #[ORM\JoinTable(name: 'user_followed_recipes')]
    private $followedRecipes;

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


    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
