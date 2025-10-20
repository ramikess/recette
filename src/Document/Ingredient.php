<?php

declare(strict_types=1);

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ODM\Document(collection: "ingredients")]
class Ingredient
{
    #[ODM\Id]
    private string $id;

    #[ODM\Field(type: "string")]
    private string $name;

    #[ODM\Field(type: "string", nullable: true)]
    private ?string $quantity = null;

    #[ODM\Field(type: "float")]
    private float $calories = 0;

    /** @var Collection<int, Recipe> */
    #[ODM\ReferenceMany(
        targetDocument: Recipe::class,
        mappedBy: "ingredients"
    )]
    private Collection $recipes;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
    }

    public function __toString(): string
    {
        return sprintf('%s (%s)', $this->name, $this->quantity ?? 'inconnu');
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

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(?string $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getCalories(): float
    {
        return $this->calories;
    }

    public function setCalories(float $calories): void
    {
        $this->calories = $calories;
    }

    public function addRecipe(Recipe $recipe): void
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
            $recipe->addIngredient($this);
        }
    }

    public function removeRecipe(Recipe $recipe): void
    {
        if ($this->recipes->removeElement($recipe)) {
            $recipe->removeIngredient($this);
        }
    }
}



