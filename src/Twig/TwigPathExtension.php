<?php

namespace App\Twig;

use App\Entity\AdvertPicture;
use App\Service\ImageResizer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class TwigPathExtension extends AbstractExtension
{
    private $imageResizer;
    private $helper;

    public function __construct(
        ImageResizer $imageResizer,
        UploaderHelper $helper
    ) {
        $this->imageResizer = $imageResizer;
        $this->helper = $helper;
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
     */
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
    public function imageAdUrl(?AdvertPicture $picture, ?int $width = null, ?int $height = null): ?string
    {
        if (null === $picture) {
            return null;
        }

        $path = $picture->getWebPath();

        if (null === $path) {
            return null;
        }

        if ('jpg' !== pathinfo($path, PATHINFO_EXTENSION)) {
            return $path;
        }

        return $this->imageResizer->resize($path, $width, $height);
    }
}
