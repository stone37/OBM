<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Pub;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom'])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'query_builder' => function (CategoryRepository $er) {
                    return $er->getEnabledQueryBuilder();
                },
                'label' => 'Catégories',
                'multiple' => true,
                'required' => true,
                'placeholder' => 'Catégories',
                'attr' => [
                    'class' => 'mdb-select md-form md-outline dropdown-stone',
                    'data-label-select-all' => 'Tout selectionnée'
                ],
            ])
            ->add('url',TextType::class, ['label' => 'Url de la pub'])
            ->add('startDate', DateType::class, [
                'label' => 'Date de debut',
                'widget' => 'single_text',
            ])
            ->add('endDate', DateType::class, [
                'label' => 'Date de Fin',
                'widget' => 'single_text',
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'Activé',
                'required' => false
            ])
            ->add('file', VichImageType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pub::class,
        ]);
    }
}
