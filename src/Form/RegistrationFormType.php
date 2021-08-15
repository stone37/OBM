<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['label' => "Nom d'utilisateur"])
            ->add('firstName', TextType::class, ['label' => 'Prénom'])
            ->add('lastName', TextType::class, ['label' => 'Nom'])
            ->add('phone', TextType::class, ['label' => 'Téléphone'])
            ->add('phoneStatus', CheckboxType::class, [
                'label' => 'Numéro whatsApp (facultatif)',
                'required' => false,
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Accepter les conditions d\'utilisations',
                'mapped' => false,
                'data' => true,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
            ]);

        /** @var ?User $user */
        $user = $builder->getData();

        if ($user && empty($user->getEmail())) {
            $builder->add('email', EmailType::class, ['label' => 'Adresse e-mail']);
        }

        if ($user && !$user->useOauth()) {
            $passwordAttrs = [
                'minlength' => 8,
                'maxlength' => 4096,
            ];
            $builder
                ->add('plainPassword', RepeatedType::class, [
                    'mapped' => false,
                    'type' => PasswordType::class,
                    'required' => true,
                    'constraints' => [
                        new NotBlank(['message' => 'Veuillez entrer un mot de passe']),
                        new Length([
                            'min' => 8,
                            'max' => 4096,
                            'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                        ]),
                    ],
                    'first_options' => ['label' => 'Mot de passe', 'attr' => $passwordAttrs],
                    'second_options' => ['label' => 'Confirmer le mot de passe', 'attr' => $passwordAttrs],
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['Default', 'Registration']
        ]);
    }
}
