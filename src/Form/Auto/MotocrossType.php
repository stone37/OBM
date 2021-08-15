<?php

namespace App\Form\Auto;

use App\Form\AdvertType as BaseAdvertFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MotocrossType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('marque', ChoiceType::class, [
                'choices' => [
                    'American ironhorse' => 'American ironhorse', 'AJP' => 'AJP', 'Aprilia' => 'Aprilia',
                    'Apollo' => 'Apollo', 'AXR' => 'AXR', 'Barossa' => 'Barossa', 'Benelli' => 'Benelli',
                    'Beta' => 'Beta', 'Big Dog' => 'Big Dog', 'Bimota' => 'Bimota', 'BMW' => 'BMW', 'Boxe' => 'Boxe',
                    'Boss Hoss' => 'Boss Hoss', 'Bourget' => 'Bourget', 'BSA' => 'BSA', 'Buell' => 'Buell',
                    'Bullit' => 'Bullit', 'Bultaco' => 'Bultaco', 'Can Am' => 'Can Am', 'CCM' => 'CCM',
                    'Ciello' => 'Ciello', 'Ch Racing' => 'Ch Racing', 'Cushman' => 'Cushman', 'Clipic' => 'Clipic',
                    'Conti motir' => 'Conti motor', 'Desperado' => 'Desperado', 'Daelim' => 'Daelim',
                    'Dakota' => 'Dakota', 'Derbi' => 'Derbi', 'Dirt bike' => 'Dirt bike', 'Ducati' => 'Ducati',
                    'Greeves' => 'Greeves', 'Harley-Davidson' => 'Harley-Davidson', 'Hodaka' => 'Hodaka',
                    'Honda' => 'Honda', 'Husqvarna' => 'Husqvarna', 'Hyosung' => 'Hyosung', 'Indian' => 'Indian',
                    'Jawa' => 'Jawa', 'JCM' => 'JCM', 'Jialing' => 'Jialing', 'Jincheng' => 'Jincheng',
                    'Jinlun' => 'Jinlun', 'JM Motor' => 'JM Motor', 'Jotagas' => 'Jotagas', 'Kawasaki' => 'Kawasaki',
                    'KTM' => 'KTM', 'Kymco' => 'Kymco', 'Lifan' => 'Lifan', 'Moto Guzzi' => 'Moto Guzzi', 'MV Agusta' => 'MV Agusta',
                    'Piaggio' => 'Piaggio', 'Polaris' => 'Polaris', 'Praga' => 'Praga', 'PZF' => 'PZF', 'Rato' => 'Rato',
                    'Razzo' => 'Razzo', 'Revatto' => 'Revatto', 'Saxon' => 'saxon', 'Scorpa' => 'Scorpa',
                    'Sea Doo' => 'Sea Doo', 'Sherco' => 'Sherco', 'Shineray' => 'Shineray', 'Ski Team' => 'Ski Team',
                    'SMC' => 'SMC', 'Spigaou' => 'Spigaou', 'Suzuki' => 'Suzuki', 'SYM' => 'SYM', 'TM' => 'TM',
                    'Titan' => 'Titan', 'Triumph' => 'Triumph', 'Ural' => 'Ural', 'Vento' => 'Vento',
                    'Victory' => 'Victory', 'Vincent' => 'Vincent', 'Von Dutch' => 'Von Dutch', 'Yamaha' => 'Yamaha',
                    'Yamasaki' => 'Yamasaki', 'Autre' => 'Autre',
                ],
                'label' => 'Marque',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone',
                ],
                'placeholder' => 'Marque',
            ])
            ->add('model', TextType::class, [
                'label' => 'Modèle (facultatif)',
                'required' => false,
            ])
            ->add('autoState', ChoiceType::class, [
                'choices' => [
                    'Irréprochable' => 'Irréprochable',
                    'Bon' => 'Bon',
                    'Moyen' => 'Moyen',
                    'Prévoir entretien' => 'Prévoir entretien',
                    'Accidenté' => 'Accidenté',
                ],
                'label' => 'Etat',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone',
                ],
                'placeholder' => 'Etat',
            ])
            ->add('autoYear', ChoiceType::class, [
                'choices' => [
                    'Avant 2000' => 'Avant 2000', '2000' => '2000', '2001' => '2001', '2002' => '2002',
                    '2003' => '2003', '2004' => '2004', '2005' => '2005', '2006' => '2006', '2007' => '2007',
                    '2008' => '2008', '2009' => '2009', '2010' => '2010', '2011'  => '2011', '2012' => '2012',
                    '2013'  => '2013', '2014'  => '2014', '2015'  => '2015', '2016'  => '2016', '2017' => '2017',
                    '2018'  => '2018', '2019'  => '2019', '2020'  => '2020', '2021'  => '2021',
                ],
                'label' => 'Année <span class="label">(facultatif)</span>',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone',
                ],
                'placeholder' => 'Année',
                'required' => false,
            ])
            ->add('kilo', IntegerType::class, [
                'label' => 'Kilométrage (Km) (facultatif)',
                'required' => false,
            ])
            ->add('cylindree', IntegerType::class, [
                'label' => 'Cylindrée (Cm3) (facultatif)',
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
                    'Essence 2 temps' => 'Essence 2 temps',
                    'Essence 4 temps' => 'Essence 4 temps',
                    'Electrique' => 'Electrique',
                    'Diesel' => 'Diesel',
                    'Autre' => 'Autre'
                ],
                'label' => 'Type de carburant <span class="label">(facultatif)</span>',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone',
                ],
                'placeholder' => 'Type de carburant',
                'required' => false,
            ])
            ->add('autoSecurity', ChoiceType::class, [
                'choices' => [
                    'ABS' => 'ABS', 'Airbag' => 'Airbag', 'Alarme-Antivol' => 'Alarme-Antivol',
                    'Coupe-Circuit' => 'Coupe-Circuit', 'Gravage antivol' => 'Gravage antivol',
                    'Amortisseur de direction' => 'Amortisseur de direction', 'Dosseret passager (Sissy-bar)' => 'Dosseret passager (Sissy-bar)',
                    'GPS' => 'GPS', 'Intercom' => 'Intercom', 'Jupe' => 'Jupe', 'Manchons' => 'Manchons',
                    'Pare-Brise' => 'Pare-Brise', 'Poignées' => 'Poignées', 'Radio' => 'Radio', 'Régulateur de vitesse' => 'Régulateur de vitesse',
                    'Selle basse' => 'Selle basse', 'Selle chauffante' => 'Selle chauffante', 'Selle confort' => 'Selle confort',
                ],
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'form-check-input filled-in'];
                },
                'label' => 'Sécurité et confort  (facultatif)',
                'placeholder' => 'Sécurité et confort',
                'required' => false,
                'expanded' => true,
                'multiple' => true
            ])
            ->add('informationExterieur', ChoiceType::class, [
                'choices' => [
                    'Pot d’échappement spécial' => 'Pot d’échappement spécial', 'Protège-cylindre' => 'Protège-cylindre',
                    'Sabot protège-carter' => 'Sabot protège-carter', 'Saccoche-réservoir' => 'Saccoche-réservoir',
                    'Saccoches rigides' => 'Saccoches rigides', 'Saccoche souple' => 'Saccoche souple',
                    'Selle spéciale' => 'Selle spéciale', 'Tapis de réservoir' => 'Tapis de réservoir',
                    'Tête de fourche' => 'Tête de fourche', 'Top-Case' => 'Top-Case', 'Valises' => 'Valises',
                    'Bulle basse' => 'Bulle basse', 'Bulle électrique' => 'Bulle électrique', 'Bulle haute' => 'Bulle haute',
                    'Bulle réglable' => 'Bulle réglable', 'Durits aviation' => 'Durits aviation', 'Kit Chrome' => 'Kit Chrome',
                    'Radio' => 'Radio', 'Peinture métallisée' => 'Peinture métallisée', 'Porte-bagages' => 'Porte-bagages',
                ],
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'form-check-input filled-in'];
                },
                'label' => 'Elements extérieurs (facultatif)',
                'placeholder' => 'Elements extérieurs',
                'required' => false,
                'expanded' => true,
                'multiple' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => ['Default', 'MC']
        ]);
    }

    public function getParent()
    {
        return BaseAdvertFormType::class;
    }
}
