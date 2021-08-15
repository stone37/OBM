<?php

namespace App\Form\Immobilier;

use App\Form\AdvertEditType as BaseAdvertFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Autre1EditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('surface', IntegerType::class, [
            'label' => 'Surface (m²)',
        ])
            ->add('immobilierState', ChoiceType::class, [
                'choices' => [
                    'Meuble' => 'Meuble',
                    'Vide' => 'Vide',
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Etat (facultatif)',
                'required' => false,
            ])
           ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => ['Default', 'Autre']
        ]);
    }

    public function getParent()
    {
        return BaseAdvertFormType::class;
    }
}



