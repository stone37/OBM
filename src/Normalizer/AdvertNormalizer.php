<?php

namespace App\Normalizer;

use App\Entity\Advert;
use InvalidArgumentException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class AdvertNormalizer implements ContextAwareNormalizerInterface
{
    private $pathNormalizer;
    private $urlGenerator;

    public function __construct(AdvertPathNormalizer $pathNormalizer, UrlGeneratorInterface $urlGenerator)
    {
        $this->pathNormalizer = $pathNormalizer;
        $this->urlGenerator = $urlGenerator;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof Advert && 'search' === $format;
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        if (!$object instanceof Advert) {
            throw new InvalidArgumentException('Unexpected type for normalization, expected Course, got '.get_class($object));
        }

        $title = $object->getTitle();
        $url = $this->pathNormalizer->normalize($object);

        return [
            'id' => (string) $object->getId(),
            'content' => MarkdownTransformer::toText((string) $object->getDescription()),
            'url' => $this->urlGenerator->generate($url['path'], $url['params']),
            'title' => $title,
            'category' => [],
            'subCategory' => [],
            'subDivision' => [],
            'type' => 'advert',
            'created_at' => $object->getCreatedAt()->getTimestamp(),
            'validated_at' => $object->getValidatedAt()->getTimestamp(),
        ];
    }
}
