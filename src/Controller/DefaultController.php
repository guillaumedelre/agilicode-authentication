<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 22/09/18
 * Time: 14:10
 */

namespace App\Controller;


use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class DefaultController extends AbstractController
{
    /**
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $encoder
     * @param JWTTokenManagerInterface     $tokenManager
     * @param UserProviderInterface        $userProvider
     *
     * @return JsonResponse
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        JWTTokenManagerInterface $tokenManager,
        UserProviderInterface $userProvider
    ) {
        if (Request::METHOD_GET === $request->getMethod()) {
            $username = $request->query->get('_username');
            $password = $request->query->get('_password');
        }

        if (Request::METHOD_POST === $request->getMethod()) {
            $username = $request->request->get('_username');
            $password = $request->request->get('_password');
        }

        $em = $this->getDoctrine()->getManager();
        $user = new User($username);
        $user->setPassword($encoder->encodePassword($user, $password));
        $em->persist($user);
        $em->flush();

        return new JsonResponse(
            [
                'username' => $username,
            ],
            Response::HTTP_OK,
            [
                'X-Bearer' => $tokenManager->create(
                    $userProvider->loadUserByUsername($username)
                ),
            ]
        );
    }

    public function token()
    {
        // The security layer will intercept this request
        return new Response('', 401);
    }
}