<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutListener
{
    private  $tokenManager;

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, CsrfTokenManagerInterface $tokenManager)
    {
        $this->entityManager = $entityManager;
        $this->tokenManager = $tokenManager;
    }

    public function onSymfonyComponentSecurityHttpEventLogoutEvent(LogoutEvent $logoutEvent){
        if($logoutEvent->getRequest()->get('delete')) {
            var_dump($logoutEvent->getRequest()->get('delete'));
            var_dump($logoutEvent->getRequest()->get('user'));
            $user = $this->entityManager->getRepository(User::class)->find($logoutEvent->getRequest()->get('user'));
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        }
        $content = "";
        $content .= var_export($logoutEvent->getRequest()->get('delete'), true);
        $content .= var_export($logoutEvent->getRequest()->get('user'), true);
        return New Response($content);
    }
}