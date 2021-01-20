<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LandingController extends AbstractController
{
    /**
     * @Route("/", name="landing")
     */
    public function index()
    {
        return $this->render('front/landing/landing.html.twig', [
            'controller_name' => 'LandingController',
        ]);
    }
}
