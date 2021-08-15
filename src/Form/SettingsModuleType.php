<?php

namespace App\Form;

use App\Entity\Settings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingsModuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('activeMessage', CheckboxType::class, [
                'label' => 'Activer la messagerie',
                'required' => false
            ])
            ->add('activeAdFavorite', CheckboxType::class, [
                'label' => 'Activer les annonces favoris',
                'required' => false
            ])
            ->add('activeAlert', CheckboxType::class, [
                'label' => 'Activer les alertes',
                'required' => false
            ])
            ->add('activeAdSimilar', CheckboxType::class, [
                'label' => 'Activer les annonces similaire',
                'required' => false
            ])
            ->add('activeCredit', CheckboxType::class, [
                'label' => 'Activer le paiement par credit',
                'required' => false
            ])
            ->add('activeCardPayment', CheckboxType::class, [
                'label' => 'Activer le paiement par carte et mobile',
                'required' => false
            ])
            ->add('activeVignette', CheckboxType::class, [
                'label' => 'Activer les vignettes',
                'required' => false
            ])
            ->add('activePub', CheckboxType::class, [
                'label' => 'Activer la publicité',
                'required' => false
            ])
            ->add('numberAdList', IntegerType::class, [
                'label' => 'Nombre d\'annonce par page',
                'required' => false
            ])
            ->add('numberAdUserList', IntegerType::class, [
                'label' => 'Nombre d\'annonce par page - dashboard',
                'required' => false
            ])
            ->add('numberFavoriteUserList', IntegerType::class, [
                'label' => 'Nombre favoris par page - dashboard',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Settings::class,
        ]);
    }
}
