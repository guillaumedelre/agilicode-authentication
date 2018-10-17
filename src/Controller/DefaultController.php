<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 22/09/18
 * Time: 14:10
 */

namespace App\Controller;


use App\Domain\Http\Request\Headers;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Negotiation\Negotiator;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

class DefaultController extends AbstractController
{
    /**
     * @var Negotiator
     */
    private $negotiator;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var JWTTokenManagerInterface
     */
    private $tokenManager;

    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @var ObjectRepository
     */
    private $userRepository;

    /**
     * DefaultController constructor.
     *
     * @param SerializerInterface          $serializer
     * @param UserPasswordEncoderInterface $encoder
     * @param JWTTokenManagerInterface     $tokenManager
     * @param RegistryInterface            $registry
     */
    public function __construct(
        SerializerInterface $serializer,
        UserPasswordEncoderInterface $encoder,
        JWTTokenManagerInterface $tokenManager,
        RegistryInterface $registry
    ) {
        $this->serializer = $serializer;
        $this->encoder = $encoder;
        $this->tokenManager = $tokenManager;
        $this->userRepository = $registry->getRepository(User::class);
        $this->em = $registry->getManagerForClass(User::class);
        $this->negotiator = new Negotiator();
    }

    /**
     * @Route(name="register", path="/register", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function register(Request $request)
    {
        $username = $request->request->get('username');
        $password = $request->request->get('password');
        if (empty($username) || empty($password)) {
            return $this->buildJsonErrorResponse('Parameters username and password are mandatory.', Response::HTTP_BAD_REQUEST);
        }

        $user = $this->userRepository->findOneByUsername($username);
        if (!empty($user)) {
            return $this->buildJsonErrorResponse("User $username already in use.", Response::HTTP_BAD_REQUEST);
        }

        $user = (new User())->setUsername($username);
        $user->setPassword($this->encoder->encodePassword($user, $password));
        $user->setRefreshToken(Uuid::uuid1()->toString());

        $this->em->persist($user);
        $this->em->flush();

        return new JsonResponse(
            $this->serializer->serialize($user, $this->getFormat($request)),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route(name="refresh", path="/refresh/{refreshToken}", methods={"GET"})
     *
     * @param Request $request
     * @param string  $refreshToken
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function refresh(Request $request, string $refreshToken)
    {
        /** @var User $user */
        $user = $this->userRepository->findOneByRefreshToken($refreshToken);
        if (empty($user)) {
            return $this->buildJsonErrorResponse('RefreshToken not found.', Response::HTTP_NOT_FOUND);
        }

        $user->setRefreshToken(Uuid::uuid1()->toString());
        $this->em->persist($user);
        $this->em->flush();

        return new JsonResponse(
            ['token' => $this->tokenManager->create($user)],
            Response::HTTP_OK,
            [Headers::X_REFRESH_TOKEN => $user->getRefreshToken()]
        );
    }

    /**
     * @return Response
     */
    public function token()
    {
        // The security layer will intercept this request
        return new Response('', 401);
    }

    /**
     * @param Request $request
     *
     * @return null|string
     */
    private function getFormat(Request $request)
    {
        $priorities = [];
        foreach ($this->getParameter('api_platform.formats') as $format => $mimeTypes) {
            $priorities[$format] = reset($mimeTypes);
        }
        $acceptHeader = $this->negotiator->getBest(
            $request->headers->get('Accept'),
            $priorities
        );

        return $request->getFormat($acceptHeader->getType());
    }

    /**
     * @param string $message
     * @param int     $statusCode
     *
     * @return JsonResponse
     */
    private function buildJsonErrorResponse(string $message, int $statusCode)
    {
        return new JsonResponse(
            [
                'code' => $statusCode,
                'message' => $message,
            ],
            $statusCode
        );
    }
}