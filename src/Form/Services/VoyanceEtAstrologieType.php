<?php

namespace App\Form\Services;

use App\Form\AdvertType as BaseAdvertFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class VoyanceEtAstrologieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    }

    public function getParent()
    {
        return BaseAdvertFormType::class;
    }
}
