<?php

namespace App\Form\Immobilier;

use App\Form\AdvertEditType as BaseAdvertFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudioAmericainEditType extends AbstractType
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
                'label' => 'Etat',
            ])
            ->add('access', ChoiceType::class, [
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
                'label' => 'Accès (facultatif)',
                'placeholder' => 'Accès (facultatif)',
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
                'label' => 'Caractéristiques extérieures (facultatif)',
                'placeholder' => 'Caractéristiques extérieures (facultatif)',
                'required' => false,
                'expanded' => true,
                'multiple' => true
            ])
            ->add('dateConstruction', ChoiceType::class, [
                'choices' => [
                    'Avant 1990' => 'Avant 1990', '1990' => '1990', '1991' => '1991', '1992' => '1992',
                    '1993' => '1993', '1994' => '1994', '1995' => '1995', '1996' => '1996', '1997' => '1997', '1998' => '1998',
                    '1999' => '1999', '2000' => '2000', '2001' => '2001', '2002' => '2002', '2003' => '2003',
                    '2004' => '2004', '2005' => '2005', '2006' => '2006', '2007' => '2007', '2008' => '2008',
                    '2009' => '2009', '2010' => '2010', '2011'  => '2011', '2012' => '2012', '2013'  => '2013',
                    '2014'  => '2014', '2015'  => '2015', '2016'  => '2016', '2017' => '2017', '2018'  => '2018',
                    '2019'  => '2019', '2020'  => '2020', '2021'  => '2021',
                ],
                'label' => 'Date de construction <span class="label">(facultatif)</span>',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone',
                ],
                'placeholder' => 'Date de construction',
                'required' => false,
            ])
            ->add('standing', ChoiceType::class, [
                'choices' => [
                    'Très bon' => 'Très bon',
                    'Bon' => 'Bon',
                    'Moyen' => 'Moyen',
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Standing (facultatif)',
                'required' => false,
            ])
            ->add('cuisine', ChoiceType::class, [
                'choices' => [
                    'Indépendante' => 'Indépendante',
                    'Américaine' => 'Américaine',
                    'Coin cuisine' => 'Coin cuisine',
                    'Equipée' => 'Equipée',
                    'Meublé' => 'Meublé',
                    'Intégrée' => 'Intégrée'
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Cuisine (facultatif)',
                'required' => false,
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
                'label' => 'Intérieur (facultatif)',
                'placeholder' => 'Intérieur (facultatif)',
                'required' => false,
                'expanded' => true,
                'multiple' => true
            ])
            ->add('serviceInclus', ChoiceType::class, [
                'choices' => [
                    'Électricité' => 'Électricité',
                    'Eau' => 'Eau',
                ],
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'form-check-input filled-in'];
                },
                'label' => 'Services inclus (facultatif)',
                'placeholder' => 'Services inclus (facultatif)',
                'required' => false,
                'expanded' => true,
                'multiple' => true
            ])
            ->add('typeSol', ChoiceType::class, [
                'choices' => [
                    'Parquet' => 'Parquet',
                    'Béton' => 'Béton',
                    'Moquette' => 'Moquette',
                    'Carrelage' => 'Carrelage',
                    'Lino' => 'Lino',
                    'Autre' => 'Autre',
                ],
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'form-check-input filled-in'];
                },
                'label' => 'Type de sol (facultatif)',
                'placeholder' => 'Type de sol (facultatif)',
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
                'label' => 'Proximité (facultatif)',
                'placeholder' => 'Proximité (facultatif)',
                'required' => false,
                'expanded' => true,
                'multiple' => true
            ])
            ->add('stateGenerale', ChoiceType::class, [
                'choices' => [
                    'Très bon' => 'Très bon',
                    'Bon' => 'Bon',
                    'Moyen' => 'Moyen',
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Etat général  (facultatif)',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => ['Default', 'StudioA']
        ]);
    }

    public function getParent()
    {
        return BaseAdvertFormType::class;
    }
}


