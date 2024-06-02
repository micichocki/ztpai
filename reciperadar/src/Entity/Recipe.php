<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Controller\RecipeController;
use App\Entity\Ingredient;
use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
    ],
    normalizationContext: [
        'groups' => ['recipe:read']
    ],
    denormalizationContext: [
        'groups' => ['recipe:write']
    ]
)]
#[GetCollection(
    uriTemplate: '/recipes',
    controller: RecipeController::class,
    normalizationContext: [
        'groups' => ['recipe:read']
    ]
)]
#[Post(
    uriTemplate: 'recipes/{recipe_id}',
    uriVariables: [
        'recipe_id' => new Link(
            fromClass: Recipe::class
        )
    ],
    controller: RecipeController::class,
    denormalizationContext: [
        'groups' => ['recipe:write']
    ])]
#[Delete(
    uriTemplate: '/recipe/{recipe_id}',
    uriVariables: [
        'recipe_id' => new Link(
            fromClass: Recipe::class
        )
    ],
    controller: RecipeController::class
)]
#[ApiResource(
    uriTemplate: 'api/users/{user_id}/recipes/{recipe_id}',
    operations: [new Post()],
    uriVariables: [
        'user_id' => new Link(
            fromProperty: 'creator',
            fromClass: Recipe::class
        ),
        'recipe_id' => new Link(
            fromClass: Recipe::class
        )
    ],
)]
#[ApiResource(
    uriTemplate: 'api/users/{user_id}/recipes/{recipe_id}',
    operations: [new Delete()],
    uriVariables: [
        'user_id' => new Link(
            fromProperty: 'creator',
            fromClass: Recipe::class
        ),
        'recipe_id' => new Link(
            fromClass: Recipe::class
        )
    ],
)]

class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['recipe:read'])]
    private $id;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['recipe:read', 'recipe:write'])]
    private string $name;

    #[Assert\NotBlank]
    #[ORM\Column(type: 'text')]
    #[Groups(['recipe:read', 'recipe:write'])]
    private string $description;

    #[Assert\Count(min: 1)]
    #[ORM\ManyToMany(targetEntity: Ingredient::class, inversedBy: 'recipes')]
    #[ORM\JoinTable(name: 'recipe_ingredient')]
    #[Groups(['recipe:read', 'recipe:write'])]
    private Collection $ingredients;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: TypeOfCuisine::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['recipe:read'])]
    private TypeOfCuisine $typeOfCuisine;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['recipe:read'])]
    private ?User $creator;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'recipe')]
    #[Groups(['recipe:read'])]
    private Collection $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->ingredients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Ingredient[]
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    /**
     * Initializes the ingredients property as an Collection.
     *
     * @param Collection $ingredients The ingredients collection
     * @return $this
     */
    public function setIngredients(ArrayCollection $ingredients): self
    {
        $this->ingredients = $ingredients;
        return $this;
    }
    public function addIngredient(Ingredient $ingredient): self
    {
        $this->ingredients[] = $ingredient;
        return $this;
    }


    public function removeIngredient(Ingredient $ingredient): self
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
    }

    public function getTypeOfCuisine(): ?TypeOfCuisine
    {
        return $this->typeOfCuisine;
    }

    public function setTypeOfCuisine(?TypeOfCuisine $typeOfCuisine): self
    {
        $this->typeOfCuisine = $typeOfCuisine;

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }
}
