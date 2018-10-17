<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 17/10/18
 * Time: 08:59
 */

namespace App\EventListener;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class JWTCreatedListener
{
    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        /**
         * @var UserInterface|User $user
         */
        $user = $event->getUser();

        $payload = $event->getData();
        $payload['fullname'] = 'Firstname Lastname';

        $event->setData($payload);

        $header = $event->getHeader();
        $header['cty'] = 'JWT';

        $event->setHeader($header);
    }
}