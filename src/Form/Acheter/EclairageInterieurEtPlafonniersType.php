<?php

namespace App\Form\Acheter;

use App\Form\AdvertType as BaseAdvertFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EclairageInterieurEtPlafonniersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'J\'offre' => 'j\'offre',
                    'Je recherche' => 'je recherche',
                    'Troc' => 'Troc',
                    'Don' => 'Don',
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Type d\'annonce',
            ])
            ->add('state', ChoiceType::class, [
                'choices' => [
                    'Neuf' => 'Neuf',
                    'Quasi neuf' => 'Quasi neuf',
                    'Occasion' => 'Occasion',
                    'A rénover' => 'A rénover'
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Etat du produit',
            ])
            ->add('aType', ChoiceType::class, [
                'choices' => [
                    'Ventilateurs de plafond' => 'Ventilateurs de plafond',
                    'Plafonniers' => 'Plafonniers',
                    'Chandeliers' => 'Chandeliers',
                    'Ventilateurs' => 'Ventilateurs',
                    'Lampe sur pied' => 'Lampe sur pied',
                    'Lampe de table & Lampe de chevet' => 'Lampe de table & Lampe de chevet',
                    'Autres ventilateurs et luminaire' => 'Autres ventilateurs et luminaire',
                ],
                'label' => 'Type <span class="label">(facultatif)</span>',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone',
                ],
                'placeholder' => 'Type',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => ['Default', 'AchatVente']
        ]);
    }

    public function getParent()
    {
        return BaseAdvertFormType::class;
    }
}

