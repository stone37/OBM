<?php

namespace App\Form\Search;

use App\Form\SearchType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

class AutoSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('marque', ChoiceType::class, [
            'choices' => [],
            'label' => 'Marque',
            'attr' => [
                'class' => 'mdb-select md-outline md-form dropdown-stone app-advert-brand',
            ],
            'placeholder' => 'Marque',
            'required' => false,
        ])
        ->add('model', ChoiceType::class, [
            'choices' => [],
            'label' => 'Modèle',
            'attr' => [
                'class' => 'mdb-select md-outline md-form dropdown-stone app-advert-model',
            ],
            'placeholder' => 'Modèle',
            'required' => false,
        ])
        ->add('autoYearMin', ChoiceType::class, [
            'choices' => [
                'Avant 2000' => 'Avant 2000', '2000' => '2000', '2001' => '2001', '2002' => '2002',
                '2003' => '2003', '2004' => '2004', '2005' => '2005', '2006' => '2006', '2007' => '2007',
                '2008' => '2008', '2009' => '2009', '2010' => '2010', '2011'  => '2011', '2012' => '2012',
                '2013'  => '2013', '2014'  => '2014', '2015'  => '2015', '2016'  => '2016', '2017' => '2017',
                '2018'  => '2018', '2019'  => '2019', '2020'  => '2020',
            ],
            'label' => 'Min',
            'attr' => [
                'class' => 'mdb-select md-outline md-form dropdown-stone',
            ],
            'placeholder' => 'Min',
            'required' => false,
        ])
        ->add('autoYearMax', ChoiceType::class, [
            'choices' => [
                'Avant 2000' => 'Avant 2000', '2000' => '2000', '2001' => '2001', '2002' => '2002',
                '2003' => '2003', '2004' => '2004', '2005' => '2005', '2006' => '2006', '2007' => '2007',
                '2008' => '2008', '2009' => '2009', '2010' => '2010', '2011'  => '2011', '2012' => '2012',
                '2013'  => '2013', '2014'  => '2014', '2015'  => '2015', '2016'  => '2016', '2017' => '2017',
                '2018'  => '2018', '2019'  => '2019', '2020'  => '2020',
            ],
            'label' => 'Max',
            'attr' => [
                'class' => 'mdb-select md-outline md-form dropdown-stone',
            ],
            'placeholder' => 'Max',
            'required' => false,
        ])
        ->add('kiloMin', IntegerType::class, [
            'label' => 'Min',
            'attr' => ['placeholder' => 'ex: 10000'],
            'required' => false,
        ])
        ->add('kiloMax', IntegerType::class, [
            'label' => 'Max',
            'attr' => ['placeholder' => 'ex: 20000'],
            'required' => false,
        ])
        ->add('typeCarburant', ChoiceType::class, [
            'choices' => [
                'Électrique' => 'Électrique',
                'Essence' => 'Essence',
                'Essence 2 temps' => 'Essence 2 temps',
                'Essence 4 temps' => 'Essence 4 temps',
                'Diesel' => 'Diesel',
                'véhicule hybride' => 'véhicule hybride',
                'Autre' => 'Autre'
            ],
            'label' => 'Type de carburant',
            'attr' => [
                'class' => 'mdb-select md-outline md-form dropdown-stone',
            ],
            'placeholder' => 'Type de carburant',
            'required' => false,
        ]);

        $builder->get('marque')->resetViewTransformers();
        $builder->get('model')->resetViewTransformers();
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
