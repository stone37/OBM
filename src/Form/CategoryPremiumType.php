<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\CategoryPremium;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CategoryPremiumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom'])
            ->add('enabled', CheckboxType::class, [
                'label' => 'Activé',
                'required' => false,
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'query_builder' => function (CategoryRepository $er) {
                    return $er->getCategoriesWithParentNotNull();
                },
                'label' => 'Catégories',
                'multiple' => true,
                'required' => true,
                'placeholder' => 'Catégories',
                'attr' => [
                    'class' => 'mdb-select md-form md-outline dropdown-stone',
                    'data-label-select-all' => 'Tout selectionnée'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CategoryPremium::class,
        ]);
    }
}
