<?php

namespace App\EventSubscriber;

use App\Repository\OrganizerRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class CustomSubscriber implements EventSubscriberInterface
{
    private $twig;

    private $repository;

    public function __construct(Environment $twig, OrganizerRepository $repository)
    {
        $this->twig = $twig;
        $this->repository = $repository;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $this->twig->addGlobal('Organizer', $this->repository->findAll()[0]);
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.controller' => 'onKernelController',
        ];
    }
}
