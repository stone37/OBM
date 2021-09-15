<?php

namespace App\Manager;

use App\Entity\User;
use App\Entity\Vignette;
use App\Service\UniqueSuiteNumberGenerator;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

final class VignetteManager
{
    private $security;
    private $em;
    private $numberGenerator;

    public  function __construct(
        Security $security,
        EntityManagerInterface $em,
        UniqueSuiteNumberGenerator $numberGenerator)
    {
        $this->security = $security;
        $this->em = $em;
        $this->numberGenerator = $numberGenerator;
    }

    /**
     * Créer une nouvelle vignette
     *
     * @return Vignette
     */
    public function createVignette(): Vignette
    {
        $vignette = (new Vignette())
                        ->setStartDate(new DateTimeImmutable())
                        ->setReference($this->numberGenerator->generate(6))
                        ->setEnabled(false);

        if ($this->security->getUser()) {

            /** @var User $user */
            $user = $this->security->getUser();

            $vignette->setFirstname($user->getFirstName());
            $vignette->setLastname($user->getLastname());
            $vignette->setEmail($user->getEmail());
            $vignette->setPhone($user->getPhone());
        }

        return $vignette;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getList(Request $request)
    {
        $category_slug     = $request->attributes->get('category_slug');
        $sub_category_slug = $request->attributes->get('sub_category_slug');

        return $this->em->getRepository(Vignette::class)->getEnabled($category_slug, $sub_category_slug);
    }

}