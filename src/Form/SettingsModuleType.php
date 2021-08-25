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
                'label' => 'Activer le paiement par carte bancaire et mobile',
                'required' => false
            ])
            ->add('activeVignette', CheckboxType::class, [
                'label' => 'Activer les vignettes',
                'required' => false
            ])
            ->add('activeParrainage', CheckboxType::class, [
                'label' => 'Activer les parrainages',
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
            ])
            ->add('parrainCredit', IntegerType::class, [
                'label' => 'Credit offert au parrain',
                'required' => false
            ])
            ->add('fioleCredit', IntegerType::class, [
                'label' => 'Credit offert au fiole',
                'required' => false
            ])
            ->add('parrainageAd', IntegerType::class, [
                'label' => 'Nombre d\'annonce pour avoir les recompenses',
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
