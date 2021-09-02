<?php

namespace App\Twig;

use App\Entity\AdvertPicture;
use App\Service\ImageResizer;
use League\Glide\Signatures\SignatureFactory;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class TwigPathExtension extends AbstractExtension
{
    private $imageResizer;
    private $resizeKey;
    private $router;
    private $helper;

    public function __construct(
        ImageResizer $imageResizer,
        UploaderHelper $helper,
        UrlGeneratorInterface $router,
        ParameterBagInterface $parameterBag
    ) {
        $this->imageResizer = $imageResizer;
        $this->helper = $helper;
        $this->router = $router;
        $this->resizeKey = $parameterBag->get('image_resize_key');
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('uploads_path', [$this, 'uploadsPath']),
            new TwigFunction('image_url', [$this, 'imageUrl']),
            new TwigFunction('image_ad_url', [$this, 'imageAdUrl']),
            new TwigFunction('image_url_raw', [$this, 'imageUrlRaw']),
            new TwigFunction('image', [$this, 'imageTag'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param string $path
     * @return string
     */
    public function uploadsPath(string $path): string
    {
        return '/uploads/'.trim($path, '/');
    }

    /**
     * @param object|null $entity
     * @param int|null $width
     * @param int|null $height
     * @return string|null

    public function imageUrl(?object $entity, ?int $width = null, ?int $height = null): ?string
    {
        if (null === $entity) {
            return null;
        }

        $path = $this->helper->asset($entity);

        if (null === $path) {
            return null;
        }

        if ('jpg' !== pathinfo($path, PATHINFO_EXTENSION)) {
            return $path;
        }

        return $this->imageResizer->resize($this->helper->asset($entity), $width, $height);
    }*/

    /**
     * @param object|null $entity
     * @param int|null $width
     * @param int|null $height
     * @param int $referenceType
     * @return string|null
     */
    public function imageUrl(?object $entity, ?int $width = null, ?int $height = null, $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): ?string
    {
        $parameters['w'] = $width;
        $parameters['h'] = $height;
        $parameters['fm'] = 'pjpg';

        $path = $this->helper->asset($entity);

        if (!$path) {
            return "";
        }

        if ('png' === substr($path, -3)) {
            $parameters['fm'] = 'png';
        }

        $parameters['s'] = SignatureFactory::create($this->resizeKey)->generateSignature($path, $parameters);
        $parameters['path'] = ltrim($path, '/');

        return $this->router->generate('app_image_resizer', $parameters, $referenceType);
    }

    /**
     * @param object|null $entity
     * @return string
     */
    public function imageUrlRaw(?object $entity): string
    {
        if (null === $entity) {
            return '';
        }

        return $this->helper->asset($entity) ?: '';
    }

    /**
     * @param object|null $entity
     * @param int|null $width
     * @param int|null $height
     * @return string|null
     */
    public function imageTag(?object $entity, ?int $width = null, ?int $height = null): ?string
    {
        $url = $this->imageUrl($entity, $width, $height);

        if (null !== $url) {
            return "<img src=\"{$url}\" width=\"{$width}\" height=\"{$height}\"/>";
        }

        return null;
    }

    /**
     * @param AdvertPicture|null $picture
     * @param int|null $width
     * @param int|null $height
     * @return string|null
     */
    public function imageAdUrl(?AdvertPicture $picture, ?int $width = null, ?int $height = null, $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): ?string
    {
        if (null === $picture) {
            return null;
        }

        $parameters['w'] = $width;
        $parameters['h'] = $height;
        $parameters['fm'] = 'pjpg';

        $path = $picture->getWebPath();

        if (null === $path) {
            return "";
        }

        if ('png' === substr($path, -3)) {
            $parameters['fm'] = 'png';
        }

        /*if ('jpg' !== pathinfo($path, PATHINFO_EXTENSION)) {
            return $path;
        }

        return $this->imageResizer->resize($path, $width, $height);*/

        $parameters['s'] = SignatureFactory::create($this->resizeKey)->generateSignature($path, $parameters);
        $parameters['path'] = ltrim($path, '/');

        return $this->router->generate('app_image_resizer', $parameters, $referenceType);
    }
}
