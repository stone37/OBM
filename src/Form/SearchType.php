<?php

namespace App\Form;

use App\Entity\City;
use App\Model\Search;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $cities = $this->em->getRepository(City::class)->getEnabledCitiesByCountryCode('ci');

        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Offre' => 'j\'offre',
                    'Recherche' => 'je recherche',
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Type d\'annonce',
                'required' => false,
            ])
            ->add('city', ChoiceType::class, [
                'choices' => $cities,
                'label' => 'Ville',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone app-advert-location-city',
                ],
                'required' => false,
                'placeholder' => 'Ville',
            ])
            ->add('zone', TextType::class, [
                'label' => 'Zone de recherche',
                'attr' => ['placeholder' => 'Zone de recherche']
            ])
            ->add('priceMin',IntegerType::class, [
                'label' => 'Min',
                'required' => false,
                'attr' => [
                    'placeholder' => 'De'
                ]
            ])
            ->add('priceMax',IntegerType::class, [
                'label' => 'Max',
                'required' => false,
                'attr' => [
                    'placeholder' => 'à'
                ]
            ])
            ->add('urgent', CheckboxType::class, [
                'label' => 'Annonce <span class="danger-color text-white py-1 px-2">urgente</span>',
                'required' => false,
            ])
        ;

        $builder->get('city')->resetViewTransformers();
        $builder->get('zone')->resetViewTransformers();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
