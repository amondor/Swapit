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

    /**
     * @Route("/swap_recap/{user}/{game}/{gametoswap}", name="swap_recap")
     */
    public function swap_recap($user=null, $game=null, $gametoswap=null)
    {
        return $this->render('front/swap/swap_recap.html.twig', [
            'controller_name' => 'SwapController',
        ]);
    }
}
