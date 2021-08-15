<?php

namespace App\Form;

use App\Entity\Zone;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SearchType;

class ZoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city', SearchType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Ville']
            ])
            ->add('zone', TextType::class, [
                'label' => 'Zone',
                'required' => false
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'Activé',
                'required' => false,
            ]);

        $builder->get('city')->resetViewTransformers();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Zone::class,
        ]);
    }
}
