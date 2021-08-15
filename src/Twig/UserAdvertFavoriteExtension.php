<?php

namespace App\Twig;

use App\Entity\Advert;
use App\Entity\Favorite;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UserAdvertFavoriteExtension extends AbstractExtension
{
    /**
     * @var Security
     */
    private $security;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('hasFavoris', array($this, 'check'))
        );
    }

    /**
     * @param Advert $advert
     * @return bool
     */
    public function check(Advert $advert): bool
    {
        $favorite = $this->em->getRepository(Favorite::class)->findOneBy([
            'user' => $this->security->getUser(),
            'advert' => $advert
        ]);

        return ($favorite) ? true : false;
    }
}
