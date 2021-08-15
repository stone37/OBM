<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Vignette;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class VignetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var EntityManagerInterface $em */
        $em = $options['em'];

        $categories = $em->getRepository(Category::class)->getWithParentNullData();

        $builder
            ->add('startDate', DateType::class, [
                'label' => 'Date de debut',
                'widget' => 'single_text',
                'attr' => ['autofocus' => true, 'class' => ['app-vignette-date']]
            ])
            ->add('category', ChoiceType::class, [
                'choices' => $categories,
                'label' => 'Catégories',
                'placeholder' => 'Catégories',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone mb-0 app-vignette-category',
                ],
            ])
            ->add('subCategory', ChoiceType::class, [
                'choices' => [],
                'label' => 'Sous catégories',
                'placeholder' => 'Sous catégories',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone mb-0 app-vignette-subcategory',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Accroche',
                'attr'  => [
                    'class' => 'form-control md-textarea',
                    'rows'  => 3,
                    'length' => 180
                ]
            ])
            ->add('siret', TextType::class, [
                'label' => 'Numéro SIRET'
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom'
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre Nom'
            ])
            ->add('email', TextType::class, [
                'label' => 'Votre email'
            ])
            ->add('phone', TextType::class, [
                'label' => 'Votre numéro de téléphone'
            ])
            ->add('societyName', TextType::class, [
                'label' => 'Nom de enseigne ou titre pub'
            ])
            ->add('siteWeb', TextType::class, [
                'label' => 'Votre site web',
                'attr'  => ['placeholder' => 'http://monsite.com']
            ])
            ->add('file', VichImageType::class)
            ->add('productId', HiddenType::class, [
                'mapped' => false,
            ]);

        $builder->get('subCategory')->resetViewTransformers();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vignette::class,
        ])->setRequired(['em']);
    }
}
