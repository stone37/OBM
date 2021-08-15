<?php

namespace App\Helper;

use App\Entity\Category;
use Symfony\Component\HttpFoundation\RequestStack;

class AdvertHelper
{
    /**
     * Retourne un form de création d'annonce selon la requete
     *
     * @return string
     */
    public function createFormType(RequestStack $request): string
    {
        return 'App\Form\\'.ucfirst($this->createFormViewPrefix($request)[0]).'\\'.$this->createFormPrefix($request).'Type';
    }

    /**
     * Retourne un form d'edition d'annonce selon la requete
     *
     * @return string
     */
    public function createFormEditType(RequestStack $request): string
    {
        return 'App\Form\\'.ucfirst($this->createFormViewPrefix($request)[0]).'\\'.$this->createFormPrefix($request).'EditType';
    }

    /**
     * Retourne un form de recherche selon la requete
     *
     * @return string
     */
    public function createSearchFormType(RequestStack $request): string
    {
        return 'App\Form\Search\\'.ucfirst($this->createFormViewPrefix($request)[0]).'SearchType';
    }

    /**
     * Retourne la vue d'un formulaire de création d'annonce selon la requete
     *
     * @return string
     */
    public function createRouteView(RequestStack $request): string
    {
        return $this->createFormViewPrefix($request)[0].'/'. $this->createFormPrefix($request).'.html.twig';
    }

    /**
     * @param RequestStack $request
     * @param Category $categoryPrincipal
     * @return string
     */
    public function showView(RequestStack $request, Category $categoryPrincipal)
    {
        $data = '';

        foreach (explode("-", $categoryPrincipal->getSlug()) as $element) {
            $data .= ucfirst($element);
        }

        return $this->createFormViewPrefix($request)[0].'/'. $data.'.html.twig';
    }

    /**
     * @param RequestStack $request
     * @return string
     */
    public function createSearchFormViewRoute(RequestStack $request): string
    {
        return  $this->createFormViewPrefix($request)[0].'search.html.twig';
    }

    /**
     * @param RequestStack $request
     * @return false|string[]
     */
    private function createFormViewPrefix(RequestStack $request)
    {
        return explode("-", $request->getCurrentRequest()->attributes->get('category_slug'));
    }

    /**
     * @param RequestStack $request
     * @return string
     */
    private function createFormPrefix(RequestStack $request)
    {
        $category_slug     = $request->getCurrentRequest()->attributes->get('category_slug');
        $sub_category_slug = $request->getCurrentRequest()->query->get('c');
        $sub_division_slug = $request->getCurrentRequest()->query->get('sc');

        if ($sub_division_slug) {
            $slug = $sub_division_slug;
        } elseif ($sub_category_slug) {
            $slug = $sub_category_slug;
        } else {
            $slug = $category_slug;
        }

        $data = '';
        foreach (explode("-", $slug) as $element) {
            $data .= ucfirst($element);
        }

        return $data;
    }
}


