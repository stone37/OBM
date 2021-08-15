<?php

namespace App\Form;

use App\Model\Search;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchGlobalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('data',TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control mdb-autocomplete search-slt input-one',
                    'placeholder' => 'Rechercher n\'importe quoi...'
                ]
            ])
            ->add('category', ChoiceType::class, [
                'choices' => [],
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'mdb-select md-form md-outline colorful-select dropdown-primary search-slt',
                ],
                'placeholder' => 'Les catégories',
            ]);

        $builder->get('category')->resetViewTransformers();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
