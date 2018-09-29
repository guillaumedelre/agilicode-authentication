<?php

namespace App\Behat\Context;

use App\Entity\User;
use Behat\Behat\Context\Context;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * FeatureContext constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @Given the jwt for :username
     */
    public function theJwtFor(string $username)
    {
        $tokenManager = $this->container->get('lexik_jwt_authentication.jwt_manager');
        $userRepository = $this->container->get('doctrine')->getRepository(User::class);
        $user = $userRepository->findOneByUsername($username);

        return $tokenManager->create($user);
    }
}
