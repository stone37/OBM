<?php

namespace App\Form;

use App\Entity\Help;
use App\Entity\HelpCategory;
use App\Repository\HelpCategoryRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HelpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('content', CKEditorType::class, [
                'label' => 'Contenu',
                'attr'  => [
                    'class' => 'form-control md-textarea',
                    'rows'  => 5,
                ],
                //'js_path' => '/public/bundles/fosckeditor/ckeditor.js',
                //'base_path' => '/public/bundles/fosckeditor/',
                'config' => array(
                    'height' => '300',
                    'toolbar' => 'full',
                ),
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'class' => HelpCategory::class,
                'choice_label' => 'name',
                'query_builder' => function (HelpCategoryRepository $er) {
                    return $er->getEnabled();
                },
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone',
                ],
                'placeholder' => 'Catégorie',
                'required' => false,
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'Activé',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Help::class,
        ]);
    }
}
