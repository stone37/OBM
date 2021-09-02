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

class ParrainageDriftSubscriber implements EventSubscriberInterface
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

        if (!$user->getInvitation()) {
            return;
        }

        if ($user->isParrainageDrift()) {
            return;
        }

        $search = (new Search())->setUser($user);
        $number = $this->em->getRepository(Advert::class)->getUserAdvertActiveNumber($search);

        if ((int)$number !== (int)$this->settings->getParrainageAd()) {
            return;
        }

        // Gestion du fiole
        /** @var Wallet $wallet */
        $wallet = ($user->getWallet())
                ->setBalance($user->getWallet()->getBalance()+$this->settings->getFioleCredit())
                ->setDeposit($user->getWallet()->getDeposit()+$this->settings->getFioleCredit());
        $user->setWallet($wallet);
        $user->setParrainageDrift(true);

        // Gestion du parrain
        /** @var User $parrain */
        $parrain = $user->getInvitation()->getOwner();

        /** @var Wallet $wallet */
        $wallet = ($parrain->getWallet())
            ->setBalance($parrain->getWallet()->getBalance()+$this->settings->getParrainCredit())
            ->setDeposit($parrain->getWallet()->getDeposit()+$this->settings->getParrainCredit());
        $parrain->setWallet($wallet);

        $this->em->flush();

        $this->senderFiole($user);
        $this->senderParrain($parrain);

        $userName = $user->getUsername() ?
            htmlentities($user->getUsername()) : htmlentities($user->getFirstName());

        $wording = '%s vous avez reçu un credit de %s';

        $this->service->notifyUser(
            $user,
            sprintf($wording, "<strong>{$userName}</strong>", "« {$this->settings->getFioleCredit()} »"),
            $event->getAdvert(),
        );

        $this->service->notifyUser(
            $parrain,
            sprintf($wording, "<strong>{$parrain->getUsername()}</strong>", "« {$this->settings->getParrainCredit()} »"),
            $event->getAdvert(),
        );
    }

    private function senderFiole(User $user)
    {
        $email = $this->mailer->createEmail('mails/drift/fiole.twig', [
            'user' => $user,
            'settings' => $this->settings,
        ])->to($user->getEmail())
            ->subject($this->settings->getName().' | Cadeau de parrainage');

        $this->mailer->send($email);
    }

    private function senderParrain(User $user)
    {
        $email = $this->mailer->createEmail('mails/drift/parrain.twig', [
            'user' => $user,
            'settings' => $this->settings,
        ])->to($user->getEmail())
            ->subject($this->settings->getName().' | Cadeau de parrainage');

        $this->mailer->send($email);
    }
}
