<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        return $this->redirectToRoute("concert_next");
    }

    /**
     * @Route("/change_locale/{locale}", name="change_locale")
     */
    public function changeLocale($locale, Request $request, RequestStack $requestStack)
    {
        // On stocke la langue dans la session
        $requestStack->getSession()->set('_locale', $locale);
        // On revient sur la page précédente
        return $this->redirect($request->headers->get('referer'));
    }
}
