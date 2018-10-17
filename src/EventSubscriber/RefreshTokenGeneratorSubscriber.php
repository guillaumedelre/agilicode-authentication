<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RefreshTokenGeneratorSubscriber implements EventSubscriberInterface
{
    /**
     * @var ObjectRepository
     */
    private $repository;

    /**
     * RefreshTokenGeneratorSubscriber constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        $this->repository = $registry->getRepository(User::class);

    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $method = $request->getMethod();
        if (
            $request->isMethodSafe(false)
            || in_array($method, [Request::METHOD_PUT, Request::METHOD_DELETE])
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

        /** @var User $data */
        $data = $request->attributes->get('data');

        if (Request::METHOD_POST !== $method || !empty($data->getRefreshToken())) {
            return;
        }

        $data->setRefreshToken(Uuid::uuid1()->toString());
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', EventPriorities::POST_DESERIALIZE],
        ];
    }
}
