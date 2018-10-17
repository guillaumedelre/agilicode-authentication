<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordEncoderSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var ObjectRepository
     */
    private $repository;

    /**
     * PasswordEncodeSubscriber constructor.
     *
     * @param UserPasswordEncoderInterface $encoder
     * @param RegistryInterface            $registry
     */
    public function __construct(UserPasswordEncoderInterface $encoder, RegistryInterface $registry)
    {
        $this->encoder = $encoder;
        $this->repository = $registry->getRepository(User::class);

    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $method = $request->getMethod();
        if (
            $request->isMethodSafe(false)
            || 'DELETE' === $method
            || !$request->attributes->get('data') instanceof User
            || !($attributes = RequestAttributesExtractor::extractAttributes($request))
            || !$attributes['receive']
            || (
                '' === ($requestContent = $request->getContent())
                && ('POST' === $method)
            )
        ) {
            return;
        }

        $encodePassword = false;

        /** @var User $data */
        $data = $request->attributes->get('data');

        if (Request::METHOD_POST === $method) {
            $encodePassword = true;
        }

        if (Request::METHOD_PUT === $method) {
            /** @var User $user */
            $user = $this->repository->findOneBy(
                [
                    'username' => $data->getUsername(),
                    'password' => $data->getPassword(),
                ]
            );

            if (empty($user)) {
                $encodePassword = true;
            }
        }

        if ($encodePassword) {
            $data->setPassword($this->encoder->encodePassword($data, $data->getPassword()));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', EventPriorities::POST_DESERIALIZE],
        ];
    }
}
