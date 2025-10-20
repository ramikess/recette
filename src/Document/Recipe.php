<?php

declare(strict_types=1);

namespace App\Document;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;


#[Document(collection: "recipes", repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ODM\Id]
    private string $id;

    #[ODM\Field(type: "string")]
    private string $name;

    #[ODM\Field(type: "string")]
    private string $content;

    /** @var Collection<int, Ingredient> */
    #[ODM\ReferenceMany(
        storeAs: "id",
        targetDocument: Ingredient::class,
        cascade: ["persist", "remove"],
        inversedBy: "recipes",
    )]
    private Collection $ingredients;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /** @return Collection<int, Ingredient> */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): void
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
            $ingredient->addRecipe($this);
        }
    }

    public function removeIngredient(Ingredient $ingredient): void
    {
        if ($this->ingredients->removeElement($ingredient)) {
            $ingredient->removeRecipe($this);
        }
    }
}
