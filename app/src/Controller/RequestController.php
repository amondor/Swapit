<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Services\Igdb;
/**
 * Class RequestController
 * @Route("/request", name="request_")
 */

class RequestController extends AbstractController
{
    
    /**
     * @Route("/", name="request", methods={"GET"})
     */
    public function authAccess(Igdb $Igdb)
    {
        dd($Igdb->searchGame('Halo'));
       
        return $this->render('request/index.html.twig', [
            'controller_name' => 'RequestController',
        ]);
    }
}
