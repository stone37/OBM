<?php

namespace App\Form;

use App\Entity\Advert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'annonce',
            ])
            ->add('traitement', ChoiceType::class, [
                'choices' => [
                    'Livraison possible' => 'Livraison possible',
                    'Expédition possible' => 'Expédition possible',
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Traitement (facultatif)',
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de annonce',
                'attr'  => [
                    'class' => 'form-control md-textarea',
                    'rows'  => 8,
                    'placeholder' => 'Décrivez précisement votre bien, en indiquant son état, ses caractéristiques, ainsi tout aure information importante pour l\'acquéreur.'
                ]
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Prix (CFA)'
            ])
            ->add('priceStatus', CheckboxType::class, [
                'label' => 'Prix négociable (facultatif)',
                'required' => false,
            ])
            ->add('location', LocationType::class, [
                'label' => 'Localisation',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Advert::class,
        ]);
    }
}
