<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Services\Igdb;
use Symfony\Bridge\PhpUnit\TextUI\Command;

/**
 * Class RequestController
 * @Route("/request", name="request_")
 */

class RequestController extends AbstractController
{
    
    /**
     * @Route("/", name="request", methods={"GET"})
     */
    public function authAccess(Igdb $Igdb, int $items = 0)
    {
        // $iteration =   $Igdb->countGames() / 500;
        // $nextItem = $items + 1;
        // $Igdb->initCron($items);

        // if ($nextItem > $iteration) {
        //     echo "telechagement de donnézq  terminer";
        //     exit;
        // }
        // $this->redirect('http://localhost:8082/request/filldata/'.$nextItem);
        
        // echo "telechagement du lot: $items ";

        $Igdb->initCron();
        return $this->render('request/index.html.twig', [
            'controller_name' => 'RequestController',
        ]);
    }
}
