<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SwapController extends AbstractController
{
    /**
     * @Route("/swap_game", name="swap_game")
     */
    public function index()
    {
        return $this->render('front/swap/swap_game.html.twig', [
            'controller_name' => 'SwapController',
        ]);
    }
}
