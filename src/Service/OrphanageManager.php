<?php

namespace App\Service;

use FilesystemIterator;
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrphanageManager
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     *
     */
    public function clear(): void
    {
        $system = new Filesystem();
        $finder = new Finder();

        try {
            $finder->in($this->container->getParameter('app.path.image_orphanage'))
                ->date('<=' . -1 * (int) 86400 . 'seconds')->files();
        } catch (InvalidArgumentException $e) {return;}

        foreach ($finder as $file) {
            $system->remove((string) $file->getRealPath());
        }

        // Now that the files are cleaned, we check if we need to remove some directories as well
        // We use a new instance of the Finder as it as a state
        $finder = new Finder();
        $finder->in($this->container->getParameter('app.path.image_orphanage'))->directories();

        $dirArray = iterator_to_array($finder, false);
        $size = count($dirArray);

        // We crawl the array backward as the Finder returns the parent first
        for ($i = $size - 1; $i >= 0; --$i) {
            $dir = $dirArray[$i];

            if (!(new FilesystemIterator((string) $dir))->valid()) {
                $system->remove((string) $dir);
            }
        }
    }

    public function initClear(SessionInterface $session): void
    {
        $system = new Filesystem();
        $finder = new Finder();

        $path = sprintf('%s/%s',
            $this->container->getParameter('app.path.image_orphanage'),
            $this->getPath($session));

        try {
            $finder->in($path)->files();
        } catch (InvalidArgumentException $e) {return;}

        foreach ($finder as $file) {
            $system->remove((string) $file->getRealPath());
        }
    }

    private function getPath(SessionInterface $session): string
    {
        return sprintf('%s/%s', $session->getId(), 'advert');
    }
}