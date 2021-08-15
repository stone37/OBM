<?php

namespace App\Form\Auto;

use App\Form\AdvertEditType as BaseAdvertFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class CanotsKayaksEtRameursEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('autoColor', ChoiceType::class, [
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

    public function getParent()
    {
        return BaseAdvertFormType::class;
    }
}
