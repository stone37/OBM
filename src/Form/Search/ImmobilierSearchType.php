<?php

namespace App\Form\Search;

use App\Form\SearchType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

class ImmobilierSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('surfaceMin', IntegerType::class, [
            'label' => 'Surface min (m²)',
            'required' => false,
        ])
            ->add('surfaceMax', IntegerType::class, [
                'label' => 'Surface max (m²)',
                'required' => false,
            ])
            ->add('nbrePiece', ChoiceType::class, [
                'label' => 'Nombre de pièces',
                'choices' => [
                    '1' => '1', '2' => '2', '2/3' => '2/3', '3' => '3',
                    '3/4' => '3/4', '4' => '4', '5' => '5', '6' => '6',
                    '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11',
                    '12' => '12', '13' => '13', '14' => '14', '15' => '15', '+15' => '+15',
                ],
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone',
                ],
                'placeholder' => 'Nombre de pièces',
            ])
            ->add('immobilierState', ChoiceType::class, [
                'choices' => [
                    'Meuble' => 'Meuble',
                    'Vide' => 'Vide',
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'état',
            ])
            ->add('nbreChambre', IntegerType::class, [
                'label' => 'Nombre de chambres',
                'required' => false,
            ])
            ->add('nbreSalleBain', IntegerType::class, [
                'label' => 'Nbre salle(s) bain',
                'required' => false,
            ])
            ->add('immobilierAcces', ChoiceType::class, [
                'choices' => [
                    'Ascenseur' => 'Ascenseur',
                    'Digicode' => 'Digicode',
                    'Interphone' => 'Interphone',
                    'Vidéophone' => 'Vidéophone',
                    'Concierge' => 'Concierge',
                ],
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'form-check-input filled-in'];
                },
                'label' => 'Accès',
                'placeholder' => 'Accès',
                'required' => false,
                'expanded' => true,
                'multiple' => true
            ])
            ->add('proximite', ChoiceType::class, [
                'choices' => [
                    'Arret de bus' => 'Arret de bus',
                    'Commerces' => 'Commerces',
                    'Gare de taxi' => 'Gare de taxi',
                    'Ecoles' => 'Ecoles',
                    'Espaces verts' => 'Espaces verts',
                    'Ligne de Gbaka' => 'Ligne de Gbaka'
                ],
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'form-check-input filled-in'];
                },
                'label' => 'Proximité',
                'placeholder' => 'Proximité',
                'required' => false,
                'expanded' => true,
                'multiple' => true
            ])
            ->add('interior', ChoiceType::class, [
                'choices' => [
                    'Aire conditionné' => 'Aire conditionné',
                    'Salle de jeux' => 'Salle de jeux',
                    'Véranda' => 'Véranda',
                    'Buanderie/lingerie' => 'Buanderie/lingerie',
                    'Double vitrage' => 'Double vitrage',
                    'Isolation acoustique' => 'Isolation acoustique',
                    'Câble/Télé' => 'Câble/Télé',
                    'Wi-Fi' => 'Wi-Fi',
                ],
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'form-check-input filled-in'];
                },
                'label' => 'Intérieur',
                'placeholder' => 'Intérieur',
                'required' => false,
                'expanded' => true,
                'multiple' => true
            ])
            ->add('exterior', ChoiceType::class, [
                'choices' => [
                    'Garage couvert' => 'Garage couvert',
                    'Garage extérieure' => 'Garage extérieure',
                    'Parking' => 'Parking',
                    'Parking couvert' => 'Parking couvert',
                    'Piscine' => 'Piscine',
                ],
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'form-check-input filled-in'];
                },
                'label' => 'Extérieure',
                'placeholder' => 'Extérieure',
                'required' => false,
                'expanded' => true,
                'multiple' => true
            ]);
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
