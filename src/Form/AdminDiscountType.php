<?php

namespace App\Form;

use App\Entity\Discount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminDiscountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('discount', IntegerType::class, ['label' => 'Valeur'])
            ->add('code', TextType::class, ['label' => 'Code de réduction'])
            ->add('utilisation', IntegerType::class, ['label' => 'Nombre d\'utilisation'])
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'choices' => [
                    'Prix fixe' => 'fixed',
                    'Pourcentage à déduire' => 'percent',
                ],
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone',
                ],
                'placeholder' => 'Type',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Discount::class,
        ]);
    }
}
