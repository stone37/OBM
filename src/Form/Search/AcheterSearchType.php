<?php

namespace App\Form\Search;

use App\Form\SearchType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class AcheterSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'J\'offre' => 'j\'offre',
                    'Je recherche' => 'je recherche',
                    'Troc' => 'Troc',
                    'Don' => 'Don',
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Type d\'annonce',
                'required' => false,
            ])
            ->add('state', ChoiceType::class, [
                'choices' => [
                    'Neuf' => 'Neuf',
                    'Quasi neuf' => 'Quasi neuf',
                    'Occasion' => 'Occasion',
                    'A rénover' => 'A rénover'
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Etat du produit',
            ])
            ->add('marque', ChoiceType::class, [
            'choices' => [],
            'label' => 'Marque',
            'attr' => [
                'class' => 'mdb-select md-outline md-form dropdown-stone',
            ],
            'placeholder' => 'Marque',
            'required' => false,
            ]);

        $builder->get('marque')->resetViewTransformers();
    }

    public function getParent()
    {
        return SearchType::class;
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
