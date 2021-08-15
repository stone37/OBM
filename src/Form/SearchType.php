<?php

namespace App\Form;

use App\Model\Search;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Offre' => 'j\'offre',
                    'Recherche' => 'je recherche',
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Type d\'annonce',
                'required' => false,
            ])
            ->add('city', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Ville']
            ])
            ->add('zone', TextType::class, [
                'label' => 'Zone de recherche',
                'attr' => ['placeholder' => 'Zone de recherche']
            ])
            ->add('priceMin',IntegerType::class, [
                'label' => 'Min',
                'required' => false,
                'attr' => [
                    'placeholder' => 'De'
                ]
            ])
            ->add('priceMax',IntegerType::class, [
                'label' => 'Max',
                'required' => false,
                'attr' => [
                    'placeholder' => 'à'
                ]
            ])
            ->add('urgent', CheckboxType::class, [
                'label' => 'Annonce <span class="danger-color text-white py-1 px-2">urgente</span>',
                'required' => false,
            ])
        ;

        $builder->get('city')->resetViewTransformers();
        $builder->get('zone')->resetViewTransformers();
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
