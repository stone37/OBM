<?php

namespace App\Form\Filter;

use App\Entity\Category;
use App\Model\Admin\AdvertSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminAdvertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $em = $options['em'];
        $categories = $em->getRepository(Category::class)->getAdminCategoryParentNull();

        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Offre' => 'j\'offre',
                    'Recherche' => 'je recherche',
                    'Troc' => 'Troc',
                    'Don' => 'Don',
                ],
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone',
                ],
                'label' => 'Type',
                'placeholder' => 'Type',
                'required' => false
            ])
            ->add('reference', TextType::class, ['label' => 'Reference', 'required' => false])
            ->add('category', ChoiceType::class, [
                'choices' => $categories,
                'label' => 'Catégories',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone app-advert-category',
                ],
                'required' => false,
                'placeholder' => 'Catégories',
            ])
            ->add('subCategory', ChoiceType::class, [
                'choices' => [],
                'label' => 'Sous catégories',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone app-advert-sub-category',
                ],
                'required' => false,
                'placeholder' => 'Sous catégories',
            ])
            ->add('subDivision', ChoiceType::class, [
                'choices' => [],
                'label' => 'Sous divisions',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone app-advert-sub-division',
                ],
                'required' => false,
                'placeholder' => 'Sous divisions',
            ])
            ->add('city', SearchType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Ville'],
                'required' => false,
            ]);

        $builder->get('city')->resetViewTransformers();
        $builder->get('subCategory')->resetViewTransformers();
        $builder->get('subDivision')->resetViewTransformers();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AdvertSearch::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ])->setRequired(['em']);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
