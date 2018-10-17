<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 18/10/18
 * Time: 09:44
 */

namespace App\Security\Http\Authentication;

use App\Domain\Http\Request\Headers;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessHandler extends \Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler
{
    /**
     * @param UserInterface|User $user
     * @param null          $jwt
     *
     * @return JWTAuthenticationSuccessResponse
     */
    public function handleAuthenticationSuccess(UserInterface $user, $jwt = null)
    {
        if (null === $jwt) {
            $jwt = $this->jwtManager->create($user);
        }

        $response = new JWTAuthenticationSuccessResponse($jwt);
        $response->headers->set(Headers::X_REFRESH_TOKEN, $user->getRefreshToken());

        $event    = new AuthenticationSuccessEvent(['token' => $jwt], $user, $response);

        $this->dispatcher->dispatch(Events::AUTHENTICATION_SUCCESS, $event);
        $response->setData($event->getData());

        return $response;
    }
}