<?php

namespace App\Form;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Ingredient Name',
                'attr' => [
                    'placeholder' => 'e.g., Flour, Sugar, Eggs...',
                    'class' => 'form-control',
                ],
            ])
            ->add('quantity', TextType::class, [
                'label' => 'Quantity',
                'required' => false,
                'attr' => [
                    'placeholder' => 'e.g., 2 cups, 1 tsp, 500g...',
                    'class' => 'form-control',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }
}
