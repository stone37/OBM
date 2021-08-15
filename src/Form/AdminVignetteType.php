<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Taxe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminVignetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('days', IntegerType::class, [
                'label' => 'Jour(s)'
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Prix (cfa)'
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'Activé',
                'required' => false,
            ])
            ->add('tva',  EntityType::class, [
                'label' => 'Tva',
                'class' => Taxe::class,
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone mb-0',
                ],
                'placeholder' => 'Tva',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'validation_groups' => ['Default', 'V']
        ]);
    }
}
