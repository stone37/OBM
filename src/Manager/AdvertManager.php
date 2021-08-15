<?php

namespace App\Manager;

use App\Entity\Advert;
use App\Entity\User;
use App\Helper\AdvertHelper;
use App\Model\Search;
use App\Service\CategoryService;
use App\Service\UniqueSuiteNumberGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\HttpFoundation\RequestStack;

final class AdvertManager
{
    private $request;
    private $em;
    private $numberGenerator;
    private $categoryService;
    private $helper;

    public  function __construct(
        RequestStack $request,
        EntityManagerInterface $em,
        UniqueSuiteNumberGenerator $numberGenerator,
        CategoryService $categoryService,
        AdvertHelper $helper)
    {
        $this->request = $request;
        $this->em = $em;
        $this->numberGenerator = $numberGenerator;
        $this->categoryService = $categoryService;
        $this->helper = $helper;
    }

    /**
     * Créer une nouvelle annonce
     *
     * @return Advert
     */
    public function createAdvert(User $user): Advert
    {
        return ($this->categoryService->addCategories(new Advert()))
                ->setUser($user)
                ->setReference($this->numberGenerator->generate(9));
    }

    /**
     * @param Advert $advert
     * @param bool $andFlush
     */
    public function updateAdvert(Advert $advert, $andFlush = true)
    {
        $this->em->persist($advert);

        if ($andFlush) {
            $this->em->flush();
        }
    }

    /**
     * Retourne un form de création d'annonce selon la requete
     *
     * @return string
     */
    public function createFormType(): string
    {
        return $this->helper->createFormType($this->request);
    }

    /**
     * Retourne un form d'edition d'annonce selon la requete
     *
     * @return string
     */
    public function createFormEditType(): string
    {
        return $this->helper->createFormEditType($this->request);
    }

    /**
     * Retourne la vue d'un formulaire de création d'annonce selon une catégorie
     *
     * @return string
     */
    public function createFormViewRoute(): string
    {
        return $this->helper->createRouteView($this->request);
    }

    /**
     * Retourne un form de création d'annonce selon une catégorie
     *
     * @return string
     */
    public function createShowView(): string
    {
        return $this->helper->showView($this->request, $this->categoryService->getCategoryPrincipale());
    }

    /**
     * Retourne un form de recherche selon une catégorie
     *
     * @return string
     */
    public function createSearchFormType(): string
    {
        return $this->helper->createSearchFormType($this->request);
    }

    /**
     * Retourne la vue d'un formulaire de recherche d'annonce selon une catégorie
     *
     * @return string
     */
    public function createSearchFormViewRoute(): string
    {
        return $this->helper->createSearchFormViewRoute($this->request);
    }

    /**
     * @param $reference
     * @return mixed
     */
    public function getAdvert($reference)
    {
        $advert = $this->getRepository()->getAdvertByReference($reference);

        return $advert;
    }

    /**
     * @param Search $search
     * @return mixed
     */
    public function getAdvertLists(Search $search)
    {
        $adverts = $this->getRepository()->getValide($search);

        return $adverts;
    }

    /**
     * @param Advert $advert
     * @return mixed
     */
    public function getAdvertSimilar(Advert $advert)
    {
        return $this->getRepository()->getSimilar($advert);
    }

    /**
     * @return array|null
     */
    public function getAdvertGallery(): ?array
    {
        return $this->getRepository()->getGallery();
    }

    /**
     * @param $category_slug
     * @param null $sub_category_slug
     * @param null $sub_division_slug
     * @return array|null
     */
    public function getAdvertVedette($category_slug, $sub_category_slug = null, $sub_division_slug = null): ?array
    {
        return $this->getRepository()->getVedette($category_slug, $sub_category_slug, $sub_division_slug);
    }

    /**
     * @return mixed
     */
    public function getAdvertHeader($category_slug, $sub_category_slug = null, $sub_division_slug = null)
    {
        return $this->getRepository()->getHeader($category_slug, $sub_category_slug, $sub_division_slug);
    }

    /**
     * @return ObjectRepository
     */
    private function getRepository(): ObjectRepository
    {
        return $this->em->getRepository(Advert::class);
    }
}