<?php

namespace App\Form\Auto;

use App\Form\AdvertType as BaseAdvertFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MotosSportType extends AbstractType
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
                'label' => 'Mod??le (facultatif)',
                'required' => false,
            ])
            ->add('autoState', ChoiceType::class, [
                'choices' => [
                    'Irr??prochable' => 'Irr??prochable',
                    'Bon' => 'Bon',
                    'Moyen' => 'Moyen',
                    'Pr??voir entretien' => 'Pr??voir entretien',
                    'Accident??' => 'Accident??',
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
                'label' => 'Ann??e',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone',
                ],
                'placeholder' => 'Ann??e',
            ])
            ->add('kilo', IntegerType::class, [
                'label' => 'Kilom??trage (Km)',
            ])
            ->add('cylindree', IntegerType::class, [
                'label' => 'Cylindr??e (Cm3) (facultatif)',
                'required' => false,
            ])
            ->add('autoColor', ChoiceType::class, [
                'choices' => [
                    'Argent' => 'Argent',
                    'Beige' => 'Beige',
                    'Blanc' => 'Blanc',
                    'Blanc cass??' => 'Blanc cass??',
                    'Bleu clair' => 'Bleu clair',
                    'Bleu fonc??' => 'Bleu fonc??',
                    'Bordeaux' => 'Bordeaux',
                    'Brun' => 'Brun',
                    'Gris clair' => 'Gris clair',
                    'Gris fonc??' => 'Gris fonc??',
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
                    'Vert fonc??' => 'Vert fonc??',
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
                    'Pare-Brise' => 'Pare-Brise', 'Poign??es' => 'Poign??es', 'Radio' => 'Radio', 'R??gulateur de vitesse' => 'R??gulateur de vitesse',
                    'Selle basse' => 'Selle basse', 'Selle chauffante' => 'Selle chauffante', 'Selle confort' => 'Selle confort',
                ],
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'form-check-input filled-in'];
                },
                'label' => 'S??curit?? et confort  (facultatif)',
                'placeholder' => 'S??curit?? et confort',
                'required' => false,
                'expanded' => true,
                'multiple' => true
            ])
            ->add('informationExterieur', ChoiceType::class, [
                'choices' => [
                    'Pot d?????chappement sp??cial' => 'Pot d?????chappement sp??cial', 'Prot??ge-cylindre' => 'Prot??ge-cylindre',
                    'Sabot prot??ge-carter' => 'Sabot prot??ge-carter', 'Saccoche-r??servoir' => 'Saccoche-r??servoir',
                    'Saccoches rigides' => 'Saccoches rigides', 'Saccoche souple' => 'Saccoche souple',
                    'Selle sp??ciale' => 'Selle sp??ciale', 'Tapis de r??servoir' => 'Tapis de r??servoir',
                    'T??te de fourche' => 'T??te de fourche', 'Top-Case' => 'Top-Case', 'Valises' => 'Valises',
                    'Bulle basse' => 'Bulle basse', 'Bulle ??lectrique' => 'Bulle ??lectrique', 'Bulle haute' => 'Bulle haute',
                    'Bulle r??glable' => 'Bulle r??glable', 'Durits aviation' => 'Durits aviation', 'Kit Chrome' => 'Kit Chrome',
                    'Radio' => 'Radio', 'Peinture m??tallis??e' => 'Peinture m??tallis??e', 'Porte-bagages' => 'Porte-bagages',
                ],
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'form-check-input filled-in'];
                },
                'label' => 'Elements ext??rieurs (facultatif)',
                'placeholder' => 'Elements ext??rieurs',
                'required' => false,
                'expanded' => true,
                'multiple' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => ['Default', 'MS']
        ]);
    }

    public function getParent()
    {
        return BaseAdvertFormType::class;
    }
}
