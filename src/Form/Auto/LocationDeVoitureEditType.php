<?php

namespace App\Form\Auto;

use App\Form\AdvertEditType as BaseAdvertFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationDeVoitureEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('marque', ChoiceType::class, [
            'choices' => [
                'Acura' => 'Acura', 'Alfa Romeo' => 'Alfa Romeo', 'AM general' => 'AM general', 'Aston Martin' => 'Aston Martin',
                'Audi' => 'Audi', 'Austin-Healey' => 'Austin-Healey', 'Bentley' => 'Bentley', 'BMW' => 'BMW',
                'Bricklin' => 'Bricklin', 'Bugatti' => 'Bugatti', 'Buick' => 'Buick', 'Cadillac' => 'Cadillac',
                'Chevrolet' => 'Chevrolet', 'Citroen' => 'Citroen', 'Chrysler' => 'Chrysler', 'Corvette' => 'Corvette',
                'Dacia' => 'Dacia', 'Daewoo' => 'Daewoo', 'Daihatsu' => 'Daihatsu', 'Datsun' => 'Datsun', 'Dodge' => 'Dodge',
                'Eagle' => 'Eagle', 'Ferrari' => 'Ferrari', 'Fiat' => 'Fiat', 'Ford' => 'Ford', 'Genesis' => 'Genesis',
                'Geo' => 'Geo', 'GMC' => 'GMC', 'Honda' => 'Honda', 'Hummer' => 'Hummer', 'Hyundai' => 'Hyundai',
                'Infinity' => 'Infinity', 'International Harvester' => 'International Harvester', 'Isuzu' => 'Isuzu', 'Jaguar' => 'Jaguar',
                'Jeep' => 'Jeep', 'Kia' => 'Kia', 'Lamborghini' => 'Lamborghini', 'Land Rover' => 'Land Rover', 'Lexus' => 'Lexus', 'Lincoln' => 'Lincoln',
                'Lotus' => 'Lotus', 'Maserati' => 'Maserati', 'Maybach' => 'Maybach', 'Mazda' => 'Mazda', 'McLaren' => 'McLaren',
                'Mercedes-benz' => 'Mercedes-benz', 'Mercury' => 'Mercury', 'MG' => 'MG', 'Mini' => 'Mini', 'Mitsubishi' => 'Mitsubishi',
                'Nissan' => 'Nissan', 'Oldsmobile' => 'Oldsmobile', 'Opel' => 'Opel', 'Peugeot' => 'Peugeot', 'Plymouth' => 'Plymouth',
                'Polestar' => 'Polestar', 'Pontiac' => 'Pontiac', 'Porsche' => 'Porsche', 'Ram' => 'Ram', 'Renault' => 'Renault',
                'Rolls-Royce' => 'Rolls-Royce', 'Saab' => 'Saab', 'Saturn' => 'Saturn', 'Scion' => 'Scion', 'Seat' => 'Seat',
                'Shelby' => 'Shelby', 'Skoda' => 'Skoda', 'Smart' => 'Smart', 'Subaru' => 'Subaru', 'Suzuki' => 'Suzuki',
                'Tata' => 'Tata', 'Tesla' => 'Tesla', 'Toyota' => 'Toyota', 'Triumph' => 'Triumph', 'Volkswagen' => 'Volkswagen',
                'Volvo' => 'Volvo', 'Autre' => 'Autre',
            ],
            'label' => 'Marque',
            'attr' => [
                'class' => 'mdb-select md-outline md-form dropdown-stone app-advert-auto-brand',
            ],
            'placeholder' => 'Marque',
        ])
            ->add('model', ChoiceType::class, [
                'choices' => [],
                'label' => 'Modèle',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone app-advert-auto-model',
                ],
                'placeholder' => 'Modèle',
            ])
            ->add('autoYear', ChoiceType::class, [
                'choices' => [
                    'Avant 2000' => 'Avant 2000', '2000' => '2000', '2001' => '2001', '2002' => '2002',
                    '2003' => '2003', '2004' => '2004', '2005' => '2005', '2006' => '2006', '2007' => '2007',
                    '2008' => '2008', '2009' => '2009', '2010' => '2010', '2011'  => '2011', '2012' => '2012',
                    '2013'  => '2013', '2014'  => '2014', '2015'  => '2015', '2016'  => '2016', '2017' => '2017',
                    '2018'  => '2018', '2019'  => '2019', '2020'  => '2020', '2021'  => '2021',
                ],
                'label' => 'Année',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone',
                ],
                'placeholder' => 'Année',
            ])
            ->add('autoType', ChoiceType::class, [
                'choices' => [
                    'Berline' => 'Berline',
                    'Bicorps' => 'Bicorps',
                    'Cabriolet' => 'Cabriolet',
                    'Camionnette' => 'Camionnette',
                    'Coupé (2 portes)' => 'Coupé (2 portes)',
                    'Familiale' => 'Familiale',
                    'Fourgonnette' => 'Fourgonnette',
                    'Fourgon' => 'Fourgon',
                    'SUV' => 'SUV',
                    'Autre' => 'Autre'
                ],
                'label' => 'Type de véhicule',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone',
                ],
                'placeholder' => 'Type de véhicule',
            ])
            ->add('boiteVitesse', ChoiceType::class, [
                'choices' => [
                    'Automatique' => 'Automatique',
                    'Manuelle' => 'Manuelle',
                    'Autre' => 'Autre',
                ],
                'label' => 'Boite à vitesse <span class="label">(facultatif)</span>',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone',
                ],
                'placeholder' => 'Boite à vitesse',
                'required' => false,
            ])
            ->add('autoColor', ChoiceType::class, [
                'choices' => [
                    'Argent' => 'Argent',
                    'Beige' => 'Beige',
                    'Blanc' => 'Blanc',
                    'Blanc cassé' => 'Blanc cassé',
                    'Bleu clair' => 'Bleu clair',
                    'Bleu foncé' => 'Bleu foncé',
                    'Bordeaux' => 'Bordeaux',
                    'Brun' => 'Brun',
                    'Gris clair' => 'Gris clair',
                    'Gris foncé' => 'Gris foncé',
                    'Havane' => 'Havane',
                    'Ivoire' => 'Ivoire',
                    'Jaune' => 'Jaune',
                    'Marron' => 'Marron',
                    'Mauve' => 'Mauve',
                    'Noir' => 'Noir',
                    'Or' => 'Or',
                    'Orange' => 'Orange',
                    'Rose' => 'Rose',
                    'Rouge' => 'Rouge',
                    'Sarcelle' => 'Sarcelle',
                    'Vert clair' => 'Vert clair',
                    'Vert foncé' => 'Vert foncé',
                    'Violet' => 'Violet',
                    'Autre' => 'Autre',
                ],
                'label' => 'Couleur <span class="label">(facultatif)</span>',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone',
                ],
                'placeholder' => 'Couleur',
                'required' => false,
            ])
            ->add('typeCarburant', ChoiceType::class, [
                'choices' => [
                    'Diesel' => 'Diesel',
                    'Electrique' => 'Electrique',
                    'Essence' => 'Essence',
                    'véhicule hybride' => 'véhicule hybride',
                    'Autre' => 'Autre'
                ],
                'label' => 'Type de carburant <span class="label">(facultatif)</span>',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone',
                ],
                'placeholder' => 'Type de carburant',
                'required' => false,
            ])
            ->add('nombrePorte', ChoiceType::class, [
                'choices' => [
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    'Autre' => 'Autre'
                ],
                'label' => 'Nombre de portes <span class="label">(facultatif)</span>',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone',
                ],
                'placeholder' => 'Nombre de portes',
                'required' => false,
            ])
            ->add('nombrePlace', ChoiceType::class, [
                'choices' => [
                    '2' => '2', '3' => '3', '4' => '4', '5' => '5',
                    '6' => '6', '7' => '7', 'Autre' => 'Autre'
                ],
                'label' => 'Nombre de places <span class="label">(facultatif)</span>',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone',
                ],
                'placeholder' => 'Nombre de places',
                'required' => false,
            ])
            ->add('autreInformation', ChoiceType::class, [
                'choices' => [
                    'Première main' => 'Première main',
                    'Véhicule non fumeur' => 'Véhicule non fumeur',
                    'Stationne dans un garage' => 'Stationne dans un garage',
                    'Barres de toit' => 'Barres de toit',
                    'Toit ouvrant' => 'Toit ouvrant',
                    'Jantes en alliage' => 'Jantes en alliage',
                    'Système de navigation' => 'Système de navigation',
                    'Bleutooth' => 'Bleutooth',
                    'Aide au stationnement' => 'Aide au stationnement',
                    'Régulateur de vitesse' => 'Régulateur de vitesse',
                    'Attache-remorque' => 'Attache-remorque',
                    'Air conditionné' => 'Air conditionné'
                ],
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'form-check-input filled-in'];
                },
                'label' => 'Autres informations (facultatif)',
                'placeholder' => 'Autres informations',
                'required' => false,
                'expanded' => true,
                'multiple' => true
            ]);

        $builder->get('model')->resetViewTransformers();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => ['Default', 'LV']
        ]);
    }

    public function getParent()
    {
        return BaseAdvertFormType::class;
    }
}
