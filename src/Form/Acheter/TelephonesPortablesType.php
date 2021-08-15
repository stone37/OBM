<?php

namespace App\Form\Acheter;

use App\Form\AdvertType as BaseAdvertFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TelephonesPortablesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('brand', ChoiceType::class, [
                'choices' => [
                    '360' => '360', 'Alcatel' => 'Alcatel', 'Apple' => 'Apple',
                    'Asus' => 'Asus', 'Blu' => 'Blu', 'HiSense' => 'HiSense', 'Hp' => 'Hp',
                    'InnJoo' => 'InnJoo', 'Infinix' => 'Infinix', 'Samsung' => 'Samsung',
                    'Acer' => 'Acer', 'Blackberry' => 'Blackberry', 'Microsoft' => 'Microsoft',
                    'Itel' => 'Itel', 'LG' => 'LG', 'Nokia' => 'Nokia', 'Techno' => 'Techno',
                    'Google' => 'Google', 'Sony' => 'Sony', 'Sony Ericsson' => 'Sony Ericsson',
                    'Oneplus' => 'Oneplus', 'Toshiba' => 'Toshiba', 'Lenovo' => 'Lenovo',
                    'Motorola' => 'Motorola', 'Huawei' => 'Huawei', 'HTC' => 'HTC',
                    'Vivo' => 'Vivo', 'Wiko' => 'Wiko', 'Xiaomi' => 'Xiaomi', 'YU' => 'YU',
                    'ZTE' => 'ZTE', 'Autre' => 'Autre',
                ],
                'label' => 'Marque',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone',
                ],
                'placeholder' => 'Marque',
            ])
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => ['Default', 'AchatVente', 'Ipad']
        ]);
    }

    public function getParent()
    {
        return BaseAdvertFormType::class;
    }
}

