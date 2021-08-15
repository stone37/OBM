<?php

namespace App\Controller\Account;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Favorite;
use App\Entity\Settings;
use App\Service\SettingsManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class FavoriteController
 * @package App\Controller\Account
 */
class FavoriteController extends AbstractController
{
    use ControllerTrait;

    /**
     * @var Settings
     */
    private $settings;

    public function __construct(SettingsManager $manager)
    {
        $this->settings = $manager->get();
    }

    /**
     * @IsGranted("ROLE_USER")
     *
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function favorite(EntityManagerInterface $em)
    {
        if (!$this->settings->isActiveAdFavorite()) throw $this->createNotFoundException('Page introuvable');

        $user = $this->getUsers($em, $this->getUser()->getId());
        $favorites = $em->getRepository(Favorite::class)->getByUser($user);

        return $this->render('user/favorite/index.html.twig', [
            'settings' => $this->settings,
            'user' => $user,
            'favorites' => $favorites,
        ]);
    }
}
