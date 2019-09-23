<?php

namespace App\EventListener\User;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CheckIsUserListener
{
    /** @var RouterInterface */
    protected $router;

    /** @var SessionInterface */
    protected $session;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @param RouterInterface $router
     * @param SessionInterface $session
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        RouterInterface $router,
        SessionInterface $session,
        TokenStorageInterface $tokenStorage,
        EntityManagerInterface $entityManager
    ) {
        $this->router = $router;
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
    }

    /**
     * On kernel.controller
     *
     * @param FilterControllerEvent $event
     *
     * @return void
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        // Don't add to the flasher if the current path does not begin with /admin
        if (!preg_match('/^\/admin/i', $event->getRequest()->getPathInfo())) {
            return;
        }

        if (null === $user = $this->tokenStorage->getToken()->getUser()) {
            return;
        }

        // Use the session to exit this listener early, if the relevant checks have already been made
        if (true === $this->session->get('user_is_user')) {
            return;
        }

        $route = $this->router->generate('user_create');

        // Check we are not already attempting to create an user.
        if (0 === strpos($event->getRequest()->getPathInfo(), $route)) {
            return;
        }

        // Check if authenticated user has an user associated with them.
        if ($user = $this->entityManager
            ->getRepository('App:User')
            ->findOneByUsername($user->getUsername())
        ) {
            $this->session->set('user_is_user', true);
        }

        if (!$user && $this->session->get('pending_user_is_user')) {
            $this->session->getFlashBag()->add(
                'warning',
                'Your user access is being set up. Please try again shortly.'
            );

            $route = $this->router->generate('homepage');
        } else {
            $this->session->getFlashBag()->add(
                'warning',
                'You cannot access the user section until you become an user. Please complete the form below to proceed.'
            );
        }

        $event->setController(function () use ($route) {
            return new RedirectResponse($route);
        });
    }
}