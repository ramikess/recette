<?php

namespace App\Controller;

use App\Document\Recipe;
use App\Enum\NutritionAnalysisType;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use App\Service\Nutrition\CaloriesCalculator;
use App\Service\Nutrition\NutritionProviderInterface;
use App\Service\Nutrition\QueryNormalizer\NutritionQueryNormalizerResolver;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/recipe')]
final class RecipeController extends AbstractController
{
    #[Route(name: 'app_recipe_index', methods: ['GET'])]
    public function index(DocumentManager $documentManager): Response
    {
        return $this->render('recipe/index.html.twig', [
            'recipes' => $documentManager->getRepository(Recipe::class)->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_recipe_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        DocumentManager $documentManager,
    ): Response {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $recipe = $form->getData();
            $documentManager->persist($recipe);
            $documentManager->flush();

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
      //  $query = $normalizerResolver->normalize($recipe, NutritionAnalysisType::Recipe);
       // $nutritionData = $nutritionProvider->analyze($query, NutritionAnalysisType::Recipe);
      //  $caloriesCalculator->hydrateIngredientsCalorie($recipe->getIngredients(), $nutritionData);

        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recipe_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Recipe $recipe,
        DocumentManager $documentManager,
    ): Response {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $documentManager->flush();

            return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_delete', methods: ['POST'])]
    public function delete(Request $request, Recipe $recipe, DocumentManager $documentManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recipe->getId(), $request->getPayload()->getString('_token'))) {
            $documentManager->remove($recipe);
            $documentManager->flush();
        }

        return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
    }
}
