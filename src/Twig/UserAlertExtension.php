<?php

namespace App\Twig;

use App\Entity\Advert;
use App\Manager\AlertManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UserAlertExtension extends AbstractExtension
{
    /**
     * @var RequestStack
     */
    private $request;

    /**
     * @var AlertManager
     */
    private $manager;

    public function __construct(RequestStack $request, AlertManager $manager)
    {
        $this->request = $request;
        $this->manager = $manager;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('hasAlert', array($this, 'check'))
        );
    }

    /**
     * @param Advert $advert
     * @return bool
     */
    public function check(): bool
    {
        $category = $this->manager->find($this->request->getCurrentRequest()->attributes->get('category_slug'));
        $subCategory = $this->manager->find($this->request->getCurrentRequest()->attributes->get('sub_category_slug'));
        $subDivision = $this->manager->find($this->request->getCurrentRequest()->attributes->get('sub_division_slug'));

        return $this->manager->hasAlert($category, $subCategory, $subDivision);
    }
}
