<?php

namespace App\Subscriber;

use App\Entity\Advert;
use App\Entity\Settings;
use App\Entity\User;
use App\Entity\Wallet;
use App\Event\AdvertValidateEvent;
use App\Mailing\Mailer;
use App\Manager\SettingsManager;
use App\Model\Search;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RegisterDriftSubscriber implements EventSubscriberInterface
{
    /** @var Settings */
    private $settings;
    private $mailer;
    private $service;
    private $em;

    public function __construct(
        Mailer $mailer,
        SettingsManager $manager,
        EntityManagerInterface $em,
        NotificationService $service)
    {
        $this->mailer = $mailer;
        $this->settings = $manager->get();
        $this->service = $service;
        $this->em = $em;
    }

    /**
     * @return array<string,string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            AdvertValidateEvent::class => 'onValidate',
        ];
    }

    /**
     * @param AdvertValidateEvent $event
     * @throws \Doctrine\ORM\ORMException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function onValidate(AdvertValidateEvent $event): void
    {
        /** @var User $user */
        $user = $event->getAdvert()->getUser();
        $search = (new Search())->setUser($user);
        $number = $this->em->getRepository(Advert::class)->getUserAdvertActiveNumber($search);

        if ((int)$number !== 1) {
            return;
        }

        if ($user->isDrift()) {
            return;
        }

        $amount = 1000;

        /** @var Wallet $wallet */
        $wallet = ($user->getWallet())
                ->setBalance($user->getWallet()->getBalance()+$amount)
                ->setDeposit($user->getWallet()->getDeposit()+$amount);
        $user->setWallet($wallet);

        $this->em->flush();

        $email = $this->mailer->createEmail('mails/drift/register.twig', [
            'user' => $user,
        ])->to($user->getEmail())
            ->subject($this->settings->getName().' | Cadeau de bienvenu');

        $this->mailer->send($email);

        $userName = $user->getUsername() ?
            htmlentities($user->getUsername()) : htmlentities($user->getFirstName());

        $wording = '%s vous avez reçu un credit de %s';

        $this->service->notifyUser(
            $event->getAdvert()->getUser(),
            sprintf($wording, "<strong>{$userName}</strong>", "« $amount »"),
            $event->getAdvert()
        );
    }
}
