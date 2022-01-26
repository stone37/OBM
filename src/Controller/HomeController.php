<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Service\SettingsManager;
use App\Storage\OrderSessionStorage;
/*use DateTime;
use Elastica\Query\BoolQuery;
use Elastica\Query\MultiMatch;
use Elastica\Query\Nested;
use Elastica\Query\Range;
use Elastica\Query\Terms;
use Elastica\Util;
use FOS\ElasticaBundle\Finder\TransformedFinder;*/
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    use ControllerTrait;

    private $settings;

    public function __construct(SettingsManager $settings)
    {
        $this->settings = $settings->get();
    }

    public function index(OrderSessionStorage $storage)
    {
        /*$storage->remove();

        $mainBoolQuery = new BoolQuery();
        $boolQuery = new BoolQuery();

        //$fieldQuery = new MatchQuery();
        //$fieldQuery->setField('title', 'Appartement');
        //$boolQuery->addShould($fieldQuery);

       // $tagsQuery = new Query\Terms('tags', ['tag1', 'tag2']);

        /*$boolQuery->addMust(new Terms('subCategoryIds', [14]));

        $nested = new Nested('category');*/

        //$slug = 'immobilier';

        /*$query = new MultiMatch();
        $query->setFields(['category.slug'])->setQuery($slug);*/


        //$filtered = new \Elastica\Query\Filtered($subquery);
        /*$categoryQuery = new MultiMatch();
        $categoryQuery->setFields(['category.slug'])->setQuery($slug);
        $categoryDomainQuery = new Nested();
        $categoryDomainQuery->setQuery($categoryQuery)->setPath('category');
        $boolQuery->addMust($categoryDomainQuery);*/

        /*$cityQuery = new MultiMatch();
        $cityQuery->setFields(['location.name'])->setQuery('Bouake');
        $cityDomainQuery = new Nested();
        $cityDomainQuery->setQuery($cityQuery)->setPath('location');
        $boolQuery->addShould($cityDomainQuery);*/

       /* $boolQuery->addFilter(new Terms('validated', [true]));
        $boolQuery->addFilter(new Terms('denied', [false]));
        $boolQuery->addFilter(new Terms('deleted', [false]));

        $date = (new DateTime())->modify('-6 month');
        $boolQuery->addFilter(new Range('validatedAt', ['gte' => Util::convertDateTimeObject($date)]));*/

        /*$cityQuery = new MultiMatch();
        $cityQuery->setFields(['location.name'])->setQuery('abidjan');
        $cityDomainQuery = new Nested();
        $cityDomainQuery->setQuery($cityQuery)->setPath('location');
        $boolQuery->addMust($cityDomainQuery);*/


        /*$boolQuery->addShould($domainQuery);
        $boolQuery->addShould($domainQuery2);*/

        //$mainBoolQuery->addMust($boolQuery);

        /*$mainBoolQuery->addFilter(new Terms('validated', [true]));
        $mainBoolQuery->addFilter(new Terms('denied', [false]));
        $mainBoolQuery->addFilter(new Terms('deleted', [false]));*/


        /*$data = $finder->findRaw($boolQuery);

        dump($data);*/



        return $this->render('site/home/index.html.twig', [
            'settings' => $this->settings
        ]);
    }

}
