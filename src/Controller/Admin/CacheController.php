<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CacheController extends AbstractController
{
    public function clean(AdapterInterface $cache): RedirectResponse
    {
        $this->addFlash('success', 'Le cache a bien été supprimé');
        $cache->clear();

        return $this->redirectToRoute('app_admin_dashboard');
    }
}

