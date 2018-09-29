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
use Negotiation\Negotiator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Serializer\SerializerInterface;

class DefaultController extends AbstractController
{
    /**
     * @param Request                      $request
     * @param SerializerInterface          $serializer
     * @param UserPasswordEncoderInterface $encoder
     * @param JWTTokenManagerInterface     $tokenManager
     * @param UserProviderInterface        $userProvider
     *
     * @return JsonResponse
     */
    public function register(
        Request $request,
        SerializerInterface $serializer,
        UserPasswordEncoderInterface $encoder,
        JWTTokenManagerInterface $tokenManager,
        UserProviderInterface $userProvider
    ) {
        $username = $request->request->get('username');
        $password = $request->request->get('password');
        if (empty($username) || empty($password)) {
            throw new BadRequestHttpException();
        }

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneByUsername($username);
        if (!empty($user)) {
            throw new ConflictHttpException();
        }

        $user = (new User())->setUsername($username);
        $user->setPassword($encoder->encodePassword($user, $password));

        $em->persist($user);
        $em->flush();

        return new JsonResponse(
            $serializer->serialize($user, $this->getFormat($request)),
            Response::HTTP_OK,
            [
                'X-Bearer' => $tokenManager->create($userProvider->loadUserByUsername($username)),
            ],
            true
        );
    }

    /**
     * @param Request $request
     *
     * @return null|string
     */
    private function getFormat(Request $request)
    {
        $negotiator = new Negotiator();
        $formats = $this->getParameter('api_platform.formats');
        $priorities = [];
        foreach ($formats as $format => $mimeTypes) {
            $priorities[$format] = reset($mimeTypes);
        }
        $acceptHeader = $negotiator->getBest(
            $request->headers->get('Accept'),
            $priorities
        );

        return $request->getFormat($acceptHeader->getType());
    }

    /**
     * @return Response
     */
    public function token()
    {
        // The security layer will intercept this request
        return new Response('', 401);
    }
}