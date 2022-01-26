<?php

namespace App\Service;

use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ApiOrphanageManager
{
    private ParameterBagInterface $bag;
    private SessionInterface $session;

    public function __construct(ParameterBagInterface $bag, SessionInterface $session)
    {
        $this->bag = $bag;
        $this->session = $session;
    }

    public function initClear(): void
    {
        if (!$this->session->isStarted()) {
            $this->session->start();
        }

        $system = new Filesystem();
        $finder = new Finder();

        $path = sprintf('%s/%s', $this->bag->get('app.path.image_orphanage'), $this->getPath());

        try {
            $finder->in($path)->files();
        } catch (InvalidArgumentException $e) {return;}

        foreach ($finder as $file) {
            $system->remove((string) $file->getRealPath());
        }
    }

    public function upload(File $file)
    {
        if (!$this->session->isStarted()) {
            $this->session->start(); 
        }

        $path = sprintf('%s/%s', $this->bag->get('app.path.image_orphanage'), $this->getPath());
        $newFilename = uniqid().'.'.$file->guessExtension();

        $file->move($path, $newFilename);

        if (!$this->session->has('app_advert_image')) {
            $this->session->set('app_advert_image', []);
        }

        $data = $this->session->get('app_advert_image');
        $data[] = [$newFilename => 0 ];
        $this->session->set('app_advert_image', $data);
    }

    public function getFindPath(): string
    {
        return sprintf('%s/%s', $this->bag->get('app.path.image_orphanage'), $this->session->getId());
    }

    private function getPath(): string
    {
        return sprintf('%s/%s', $this->session->getId(), 'advert');
    }
}
