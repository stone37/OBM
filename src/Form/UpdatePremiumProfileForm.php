<?php

namespace App\Form;

use App\Dto\ProfilePremiumUpdateDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method ProfilePremiumUpdateDto getData()
 */
class UpdatePremiumProfileForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder->add('email', EmailType::class, [
                'required' => true,
            ])
            ->add('username', TextType::class, [
                'required' => false,
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('phone', TextType::class, [
                'label' => 'Numéro de téléphone',
            ])
            ->add('phoneStatus', CheckboxType::class, [
                'label' => 'Numéro whatsapp',
                'required' => false,
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'required' => false
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'required' => false
            ])
            ->add('facebook', TextType::class, [
                'label' => 'Adresse facebook',
                'required' => false
            ])
            ->add('twitter', TextType::class, [
                'label' => 'Adresse twitter',
                'required' => false
            ])
            ->add('youtube', TextType::class, [
                'label' => 'Adresse youtube',
                'required' => false
            ])
            ->add('instagram', TextType::class, [
                'label' => 'Adresse twitter',
                'required' => false
            ])
            ->add('linkedin', TextType::class, [
                'label' => 'Adresse linkedin',
                'required' => false
            ])
            ->add('webSite', TextType::class, [
                'label' => 'Url du site internet',
                'required' => false
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'entreprise',
                'required' => false
            ])
            ->add('societyCity', TextType::class, [
                'label' => 'Ville de l\'entreprise',
                'required' => false
            ])
            ->add('societyDistrict', TextType::class, [
                'label' => 'Zone de l\'entreprise',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProfilePremiumUpdateDto::class,
        ]);
    }
}
