<?php

namespace App\Form\Auto;

use App\Form\AdvertEditType as BaseAdvertFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AssuranceEtFinancementAutoEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    }

    public function getParent()
    {
        return BaseAdvertFormType::class;
    }
}
