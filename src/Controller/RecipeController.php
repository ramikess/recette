<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Enum\NutritionAnalysisType;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use App\Service\Nutrition\CaloriesCalculator;
use App\Service\Nutrition\NutritionProviderInterface;
use App\Service\Nutrition\QueryNormalizer\NutritionQueryNormalizerResolver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/recipe')]
final class RecipeController extends AbstractController
{
    #[Route(name: 'app_recipe_index', methods: ['GET'])]
    public function index(RecipeRepository $recipeRepository): Response
    {
        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_recipe_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $recipe = $form->getData();
            $entityManager->persist($recipe);
            $entityManager->flush();

            return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipe/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_show', methods: ['GET'])]
    public function show(
        Recipe $recipe,
        NutritionProviderInterface $nutritionProvider,
        NutritionQueryNormalizerResolver $normalizerResolver,
        CaloriesCalculator $caloriesCalculator,
    ): Response {
        $query = $normalizerResolver->normalize($recipe, NutritionAnalysisType::Recipe);
        $nutritionData = $nutritionProvider->analyze($query, NutritionAnalysisType::Recipe);
        $caloriesCalculator->hydrateIngredientsCalorie($recipe->getIngredients(), $nutritionData);

        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recipe_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Recipe $recipe,
        EntityManagerInterface $entityManager,
    ): Response {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }
}
