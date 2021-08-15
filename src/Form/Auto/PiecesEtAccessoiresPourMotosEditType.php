<?php

namespace App\Form\Auto;

use App\Form\AdvertEditType as BaseAdvertFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PiecesEtAccessoiresPourMotosEditType extends AbstractType
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
            'label' => 'Marque <span class="label">(facultatif)</span>',
            'attr' => [
                'class' => 'mdb-select md-outline md-form dropdown-stone',
            ],
            'placeholder' => 'Marque',
            'required' => false,
            ])
            ->add('model', TextType::class, [
                'label' => 'Modèle (facultatif)',
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
        ;
    }

    public function getParent()
    {
        return BaseAdvertFormType::class;
    }
}
