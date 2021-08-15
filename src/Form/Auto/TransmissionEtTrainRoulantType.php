<?php

namespace App\Form\Auto;

use App\Form\AdvertType as BaseAdvertFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class TransmissionEtTrainRoulantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('marque', ChoiceType::class, [
            'choices' => [
                'Acura' => 'Acura', 'Alfa Romeo' => 'Alfa Romeo', 'AM general' => 'AM general', 'Aston Martin' => 'Aston Martin',
                'Audi' => 'Audi', 'Austin-Healey' => 'Austin-Healey', 'Bentley' => 'Bentley', 'BMW' => 'BMW',
                'Bricklin' => 'Bricklin', 'Bugatti' => 'Bugatti', 'Buick' => 'Buick', 'Cadillac' => 'Cadillac',
                'Chevrolet' => 'Chevrolet', 'Citroen' => 'Citroen', 'Chrysler' => 'Chrysler', 'Corvette' => 'Corvette',
                'Dacia' => 'Dacia', 'Daewoo' => 'Daewoo', 'Daihatsu' => 'Daihatsu', 'Datsun' => 'Datsun', 'Dodge' => 'Dodge',
                'Eagle' => 'Eagle', 'Ferrari' => 'Ferrari', 'Fiat' => 'Fiat', 'Ford' => 'Ford', 'Genesis' => 'Genesis',
                'Geo' => 'Geo', 'GMC' => 'GMC', 'Honda' => 'Honda', 'Hummer' => 'Hummer', 'Hyundai' => 'Hyundai',
                'Infinity' => 'Infinity', 'International Harvester' => 'International Harvester', 'Isuzu' => 'Isuzu', 'Jaguar' => 'Jaguar',
                'Jeep' => 'Jeep', 'Kia' => 'Kia', 'Lamborghini' => 'Lamborghini', 'Land Rover' => 'Land Rover', 'Lexus' => 'Lexus', 'Lincoln' => 'Lincoln',
                'Lotus' => 'Lotus', 'Maserati' => 'Maserati', 'Maybach' => 'Maybach', 'Mazda' => 'Mazda', 'McLaren' => 'McLaren',
                'Mercedes-benz' => 'Mercedes-benz', 'Mercury' => 'Mercury', 'MG' => 'MG', 'Mini' => 'Mini', 'Mitsubishi' => 'Mitsubishi',
                'Nissan' => 'Nissan', 'Oldsmobile' => 'Oldsmobile', 'Opel' => 'Opel', 'Peugeot' => 'Peugeot', 'Plymouth' => 'Plymouth',
                'Polestar' => 'Polestar', 'Pontiac' => 'Pontiac', 'Porsche' => 'Porsche', 'Ram' => 'Ram', 'Renault' => 'Renault',
                'Rolls-Royce' => 'Rolls-Royce', 'Saab' => 'Saab', 'Saturn' => 'Saturn', 'Scion' => 'Scion', 'Seat' => 'Seat',
                'Shelby' => 'Shelby', 'Skoda' => 'Skoda', 'Smart' => 'Smart', 'Subaru' => 'Subaru', 'Suzuki' => 'Suzuki',
                'Tata' => 'Tata', 'Tesla' => 'Tesla', 'Toyota' => 'Toyota', 'Triumph' => 'Triumph', 'Volkswagen' => 'Volkswagen',
                'Volvo' => 'Volvo', 'Autre' => 'Autre',
                ],
            'label' => 'Marque <span class="label">(facultatif)</span>',
            'attr' => [
                'class' => 'mdb-select md-outline md-form dropdown-stone',
            ],
            'placeholder' => 'Marque',
            'required' => false,
        ]);
    }

    public function getParent()
    {
        return BaseAdvertFormType::class;
    }
}
