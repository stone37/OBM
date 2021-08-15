<?php

namespace App\Service;

use App\Entity\User;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Security\Core\User\UserInterface;

class CookieGenerator
{
    private $secret;
    private $notificationService;

    public function __construct(string $secret, NotificationService $notificationService)
    {
        $this->secret = $secret;
        $this->notificationService = $notificationService;
    }

    /**
     * @param User|UserInterface $user
     * @return Cookie
     */
    public function generate(User $user): Cookie
    {
        $channels = array_map(fn (string $channel) => "/notifications/$channel", $this->notificationService->getChannelsForUser($user));

        $token = (new Builder())
            ->withClaim('mercure', [
                'subscribe' => $channels,
            ])
            ->getToken(new Sha256(), new Key($this->secret));

        return Cookie::create('mercureAuthorization', $token, 0, '/.well-known/mercure');
    }
}

