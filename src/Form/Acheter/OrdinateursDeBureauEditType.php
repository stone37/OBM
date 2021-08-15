<?php

namespace App\Form\Acheter;

use App\Form\AdvertEditType as BaseAdvertFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdinateursDeBureauEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('brand', ChoiceType::class, [
                'choices' => [
                    'Acer' => 'Acer', 'Apple' => 'Apple', 'Asus' => 'Asus', 'Compaq' => 'Compaq',
                    'Dell' => 'Dell', 'Gateway' => 'Gateway', 'Hp' => 'Hp', 'IBM' => 'IBM',
                    'Intel' => 'Intel', 'Lenovo' => 'Lenovo', 'Samsung' => 'Samsung',
                    'Toshiba' => 'Toshiba', 'Sony' => 'Sony', 'MSI' => 'MSI', 'Autre' => 'Autre',
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
            'validation_groups' => ['Default', 'AchatVente', 'Obureau']
        ]);
    }

    public function getParent()
    {
        return BaseAdvertFormType::class;
    }
}

