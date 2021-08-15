<?php

namespace App\Form\Auto;

use App\Form\AdvertEditType as BaseAdvertFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VedettesEtBateauxAMoteurEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('marque', ChoiceType::class, [
                'choices' => [
                    'Ar marine' => 'Ar marine', 'Abatte' => 'Abatte', 'Absolute' => 'Absolute', 'ACA' => 'ACA',
                    'ACM' => 'ACM', 'Acquaviva' => 'Acquaviva', 'Acroplast' => 'Acroplast', 'Adagio' => 'Adagio',
                    'Adler' => 'Adler', 'Admiral cantieri' => 'Admiral cantieri', 'Aga marine' => 'Aga marine',
                    'Aicon' => 'Aicon', 'Akerboom' => 'Akerboom', 'Akis' => 'Akis', 'Alaska' => 'Alaska', 'AlBacore' => 'AlBacore',
                    'Albatros' => 'Albatros', 'Alden yachts' => 'Alden yachts', 'Alexander marine' => 'Alexander marine',
                    'Alfamarine' => 'Alfamarine', 'Alize' => 'Alize', 'Allemand' => 'Allemand', 'Alson' => 'Alson',
                    'Altena' => 'Altena', 'Amer' => 'Amer', 'Amerglass' => 'Amerglass', 'American marine' => 'American marine',
                    'Ancas queen' => 'Ancas queen', 'Antares' => 'Antares', 'Apreamare' => 'Apreamare', 'Aqualunox' => 'Aqualunox',
                    'Aquamar' => 'Aquamar', 'Aquanaut' => 'Aquanaut', 'Aquasilure' => 'Aquasilure', 'Aquatron' => 'Aquatron',
                    'Arc eyre' => 'Arc eyre', 'Arca' => 'Arca', 'Archangeli' => 'Archangeli', 'Arcoa' => 'Arcoa',
                    'Arental' => 'Arental', 'Argus' => 'Argus', 'Arimar' => 'Arimar', 'Arkos' => 'Arkos', 'Ars mare' => 'Ars mare',
                    'Artaban' => 'Artaban', 'Arvor' => 'Arvor', 'ASC' => 'ASC', 'Asterie' => 'Asterie', 'Astondoa' => 'Astondoa',
                    'Atlantis' => 'Atlantis', 'Avon' => 'Avon', 'Aziez' => 'Aziez', 'Azimut' => 'Azimut', 'B2 marine' => 'B2 marine',
                    'Baglietto' => 'Baglietto', 'Baia' => 'Baia', 'Baja' => 'Baja', 'Balt' => 'Balt', 'Bat' => 'Bat',
                    'Bateau bois' => 'Bateau bois', 'Bateau loisirs' => 'Bateau loisirs', 'Bavaria' => 'Bavaria',
                    'Bayliner' => 'Bayliner', 'Beacher' => 'Beacher', 'Beluga' => 'Beluga', 'Beneteau' => 'Beneteau',
                    'Benetti' => 'Benetti', 'Bertram' => 'Bertram', 'Best boat' => 'Best boat', 'Bic' => 'Bic',
                    'Bimax' => 'Bimax', 'Biot' => 'Biot', 'Birchwood' => 'Birchwood', 'Blackfin' => 'Blackfin',
                    'Blue ocean' => 'Blue ocean', 'Bluestar' => 'Bluestar', 'BMB' => 'BMB', 'Bombard' => 'Bombard',
                    'Bombardier' => 'Bombardier', 'Boston whaler' => 'Boston whaler', 'Botnia' => 'Botnia', 'Boudignon' => 'Boudignon',
                    'Boye' => 'Boye', 'Brabankruiser' => 'Brabankruiser', 'Bremaud' => 'Bremaud', 'Bresan' => 'Bresan',
                    'Broom' => 'Broom', 'Bruceroberts' => 'Bruceroberts', 'Bruno abbate' => 'Bruno abbate', 'BSC' => 'BSC',
                    'Bugari' => 'Bugari', 'Bulotier caseyeur' => 'Bulotier caseyeur', 'Bunkerboot' => 'Bunkerboot',
                    'Buonomo' => 'Buonomo', 'Cad marine' => 'Cad marine', 'Cadou' => 'Cadou', 'Canados' => 'Canados',
                    'Canot breton' => 'Canot breton', 'Carver' => 'Carver', 'Catana' => 'Catana', 'Cayman' => 'Cayman',
                    'Centiry' => 'Centiry', 'Challenger' => 'Challenger', 'Chiavani' => 'Chiavani', 'Chiberta' => 'Chiberta',
                    'Classic craft' => 'Classic craft', 'Cobalt' => 'Cobalt', 'Cobia' => 'Cobia', 'Concore' => 'Concore',
                    'Cruiser' => 'Cruiser', 'Cytra' => 'Cytra', 'Darragh' => 'Darragh', 'De ruiter' => 'De ruiter',
                    'De stephano' => 'De stephano', 'Delavergne' => 'Delavergne', 'Delfyn' => 'Delfyn', 'Dell quay' => 'Dell quay',
                    'Doral' => 'Doral', 'Drago' => 'Drago', 'Eastbay' => 'Eastbay', 'Egemar' => 'Egemar', 'Egg harbor' => 'Egg harbor', 'Elan' => 'Elan',
                    'Elling' => 'Elling', 'Ester' => 'Ester', 'Etap' => 'Etap', 'Eurobanker' => 'Eurobanker', 'Everset' => 'Everset',
                    'fairline' => 'fairline', 'Falcon' => 'Falcon', 'First' => 'First', 'Fjord' => 'Fjord', 'Flash boat' => 'Flash boat',
                    'Flipper' => 'Flipper', 'Forbina' => 'Forbina', 'Fred' => 'Fred', 'Freeman' => 'Freeman',
                    'Galaxy' => 'Galaxy', 'Gallart' => 'Gallart', 'Garcia' => 'Garcia', 'Giogi' => 'Giogi', 'Glastron' => 'Glastron',
                    'Granchi' => 'Granchi', 'Hard' => 'Hard', 'Hartleer' => 'Hartleer', 'Hershine' => 'Hershine',
                    'Hiptimco' => 'Hiptimco', 'Honda' => 'Honda', 'Horizon' => 'Horizon', 'Ilver' => 'Ilver',
                    'Italcraft' => 'Italcraft', 'Jamaica' => 'Jamaica', 'Jicey' => 'Jicey', 'Karnic' => 'Karnic',
                    'Kelt marine' => 'Kelt marine', 'Lagoon' => 'Lagoon', 'Lambro' => 'Lambro', 'Larson' => 'Larson',
                    'Laver' => 'Laver', 'Lema' => 'Lema', 'Leopard' => 'Leopard', 'Litton' => 'Litton', 'Lomac' => 'Lomac',
                    'Mach' => 'Mach', 'Mainship' => 'Mainship', 'Makma yachting' => 'Makma yachting', 'Malibu' => 'Malibu',
                    'Marex' => 'Marex', 'Marinex' => 'Marinex', 'Nautica' => 'Nautica', 'Neptune' => 'Neptune',
                    'Nuova jolly' => 'Nuova jolly', 'Obe' => 'Obe', 'Ocean yacht' => 'Ocean yacht', 'OMC' => 'OMC',
                    'Orca' => 'Orca', 'Orkney' => 'Orkney', 'Pacific craft' => 'Pacific craft', 'Phoenix' => 'Phoenix',
                    'Pholas' => 'Pholas', 'Picton' => 'Picton', 'Polyesta' => 'Polyesta', 'Princess' => 'Princess',
                    'Rancraft' => 'Rancraft', 'Regal' => 'Regal', 'Renaud' => 'Renaud', 'Revenger' => 'Revenger',
                    'Riamar' => 'Riamar', 'Ribeye' => 'Ribeye', 'Ring' => 'Ring', 'Rio' => 'Rio', 'Rocca' => 'Rocca',
                    'Sarnico' => 'Sarnico', 'Scanner' => 'Scanner', 'Sciallino' => 'Sciallino', 'Sea ray' => 'Sea ray',
                    'Selene' => 'Selene', 'Selva' => 'Selva', 'Serpilli' => 'Serpilli', 'Sessa' => 'Sessa', 'Shetland' => 'Shetland',
                    'Smartliner' => 'Smartliner', 'Sonic' => 'Sonic', 'Splash' => 'Splash', 'Starcraft' => 'Starcraft',
                    'Stingher' => 'Stingher', 'Stip' => 'Stip', 'Stratos' => 'Stratos', 'Stripper' => 'Stripper',
                    'Sunday' => 'Sunday', 'Supra' => 'Supra', 'Targa' => 'Targa', 'Tavlor' => 'Tavlor', 'Technomare' => 'Technomare',
                    'Tecnomar' => 'Tecnomar', 'Tempest' => 'Tempest', 'Terhi' => 'Terhi', 'Teychan' => 'Teychan',
                    'Tiara' => 'Tiara', 'Tomcat' => 'Tomcat', 'Trawler' => 'Trawler', 'Ultramar' => 'Ultramar',
                    'Urania' => 'Urania', 'Valk' => 'Valk', 'Viking' => 'Viking', 'Vitech' => 'Vitech', 'Voyager marine' => 'Voyager marine',
                    'White shark' => 'White shark', 'Wind' => 'Wind', 'Winner' => 'Winner', 'Zenith' => 'Zenith',
                    'Zodiac' => 'Zodiac', 'Autre' => 'Autre',
                ],
                'label' => 'Marque',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone',
                ],
                'placeholder' => 'Marque',
            ])
            ->add('model', TextType::class, [
                'label' => 'Modèle (facultatif)',
                'required' => false,
            ])
            ->add('autoYear', ChoiceType::class, [
                'choices' => [
                    'Avant 2000' => 'Avant 2000', '2000' => '2000', '2001' => '2001', '2002' => '2002',
                    '2003' => '2003', '2004' => '2004', '2005' => '2005', '2006' => '2006', '2007' => '2007',
                    '2008' => '2008', '2009' => '2009', '2010' => '2010', '2011'  => '2011', '2012' => '2012',
                    '2013'  => '2013', '2014'  => '2014', '2015'  => '2015', '2016'  => '2016', '2017' => '2017',
                    '2018'  => '2018', '2019'  => '2019', '2020'  => '2020', '2021'  => '2021',
                ],
                'label' => 'Année <span class="label">(facultatif)</span>',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone',
                ],
                'placeholder' => 'Année',
                'required' => false,
            ])
            ->add('typeCarburant', ChoiceType::class, [
                'choices' => [
                    'Essence 2 temps' => 'Essence 2 temps',
                    'Essence 4 temps' => 'Essence 4 temps',
                    'Electrique' => 'Electrique',
                    'Diesel' => 'Diesel',
                    'Autre' => 'Autre'
                ],
                'label' => 'Type de carburant <span class="label">(facultatif)</span>',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone',
                ],
                'placeholder' => 'Type de carburant',
                'required' => false,
            ])
            ->add('autoColor', ChoiceType::class, [
                'choices' => [
                    'Argent' => 'Argent',
                    'Beige' => 'Beige',
                    'Blanc' => 'Blanc',
                    'Blanc cassé' => 'Blanc cassé',
                    'Bleu clair' => 'Bleu clair',
                    'Bleu foncé' => 'Bleu foncé',
                    'Bordeaux' => 'Bordeaux',
                    'Brun' => 'Brun',
                    'Gris clair' => 'Gris clair',
                    'Gris foncé' => 'Gris foncé',
                    'Havane' => 'Havane',
                    'Ivoire' => 'Ivoire',
                    'Jaune' => 'Jaune',
                    'Marron' => 'Marron',
                    'Mauve' => 'Mauve',
                    'Noir' => 'Noir',
                    'Or' => 'Or',
                    'Orange' => 'Orange',
                    'Rose' => 'Rose',
                    'Rouge' => 'Rouge',
                    'Sarcelle' => 'Sarcelle',
                    'Vert clair' => 'Vert clair',
                    'Vert foncé' => 'Vert foncé',
                    'Violet' => 'Violet',
                    'Autre' => 'Autre',
                ],
                'label' => 'Couleur <span class="label">(facultatif)</span>',
                'attr' => [
                    'class' => 'mdb-select md-outline md-form dropdown-stone',
                ],
                'placeholder' => 'Couleur',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => ['Default', 'VBM']
        ]);
    }


    public function getParent()
    {
        return BaseAdvertFormType::class;
    }
}
