<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Location;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
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
            ->add('name', ChoiceType::class, [
                'choices' => $cities,
                'label' => 'Ville',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone app-advert-location-city',
                ],
                'required' => false,
                'placeholder' => 'Ville',
            ])
            ->add('detail', TextType::class, [
                'label' => 'Zone',
                'required' => false
            ]);

        $builder->get('name')->resetViewTransformers();
        $builder->get('detail')->resetViewTransformers();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
