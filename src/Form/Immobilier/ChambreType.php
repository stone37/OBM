<?php

namespace App\Form\Immobilier;

use App\Form\AdvertType as BaseAdvertFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChambreType extends AbstractType
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
            'validation_groups' => ['Default', 'Chambre']
        ]);
    }

    public function getParent()
    {
        return BaseAdvertFormType::class;
    }
}


