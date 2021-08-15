<?php

namespace App\Queue\Handler;

//use App\Domain\Notification\NotificationService;
use App\Mailing\Mailer;
use App\Queue\Message\ServiceMethodMessage;
use App\Service\NotificationService;
use Psr\Container\ContainerInterface;
use Symfony\Component\Mailer\MailerInterface;
//use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class ServiceMethodMessageHandler implements MessageHandlerInterface, ServiceSubscriberInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(ServiceMethodMessage $message): void
    {
        /** @var callable $callable */
        $callable = [
            $this->container->get($message->getServiceName()),
            $message->getMethod(),
        ];

        call_user_func_array($callable, $message->getParams());
    }

    public static function getSubscribedServices()
    {
        return [
            MailerInterface::class => MailerInterface::class,
            Mailer::class => Mailer::class,
            //PublisherInterface::class => PublisherInterface::class,
            //NotificationService::class => NotificationService::class,
        ];
    }
}
