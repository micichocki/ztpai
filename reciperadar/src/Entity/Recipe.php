<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ApiResource]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    #[ORM\Column(type: 'string', length: 255)]
    private string $name;
    #[Assert\NotBlank]
    #[ORM\Column(type: 'text')]
    private string $description;
    #[Assert\Count(min: 1)]
    #[ORM\ManyToMany(targetEntity: Ingredient::class, inversedBy: 'recipes')]
    #[ORM\JoinTable(name: 'recipe_ingredient')]
    private Collection $ingredients;
    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: TypeOfCuisine::class)]
    #[ORM\JoinColumn(nullable: false)]
    private TypeOfCuisine $typeOfCuisine;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'recipe')]
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
    public function setIngredients($ingredients): self
    {
        $this->ingredients = new ArrayCollection($ingredients);

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
    public function getComments(): Collection
    {
        return $this->comments;
    }

}
