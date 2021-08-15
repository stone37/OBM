<?php

namespace App\Form;

use App\Model\ContactData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Votre nom et prénom',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre email',
            ])
            ->add('phone', TextType::class, [
                'label' => 'Votre numéro',
            ])
            ->add('content',  TextareaType::class, [
                'label' => 'Votre message',
                'attr' => [
                    'class' => 'md-textarea form-control',
                    'rows' => 6
                ],
            ])
            ->add('recaptchaToken', HiddenType::class, [
                'mapped' => false,
                'attr' => ['class' => 'app-recaptchaToken']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContactData::class,
        ]);
    }
}
