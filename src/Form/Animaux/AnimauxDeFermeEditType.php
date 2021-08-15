<?php

namespace App\Form\Animaux;

use App\Form\AdvertEditType as BaseAdvertFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AnimauxDeFermeEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    }

    public function getParent()
    {
        return BaseAdvertFormType::class;
    }
}
