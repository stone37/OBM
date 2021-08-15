<?php

namespace App\Form\Auto;

use App\Form\AdvertType as BaseAdvertFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CamionsLourdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('autoYear', ChoiceType::class, [
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
            ->add('kilo', IntegerType::class, [
                'label' => 'Kilométrage (Km)',
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => ['Default', 'CL']
        ]);
    }

    public function getParent()
    {
        return BaseAdvertFormType::class;
    }
}
