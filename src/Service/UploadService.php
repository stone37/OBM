<?php

namespace App\Service;

use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class UploadService
{
    /**
     * @var ParameterBagInterface
     */
    private $parameter;

    public function __construct(ParameterBagInterface $parameter)
    {
        $this->parameter = $parameter;
    }

    /**
     * @param SessionInterface $session
     * @return Finder
     */
    public function getFilesUpload(SessionInterface $session): Finder
    {
        $finder = new Finder();

        try {
            $finder->in($this->getFindPath($session))->files();
        } catch (InvalidArgumentException $e) {
            $finder->append([]);
        }

        return $finder;
    }

    /**
     * @param SessionInterface $session
     * @return string
     */
    private function getFindPath(SessionInterface $session): string
    {
        return sprintf('%s/%s',
            $this->parameter->get('app.path.image_orphanage'),
            $this->getPath($session));
    }

    /**
     * @param SessionInterface $session
     * @return string
     */
    private function getPath(SessionInterface $session): string
    {
        return sprintf('%s/%s', $session->getId(), 'advert');
    }

}