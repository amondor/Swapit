<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfilPageController extends AbstractController
{
    /**
     * @Route("/profil/page", name="profil_page")
     */
    public function index()
    {

    
        return $this->render('profil_page/index.html.twig', [
            'controller_name' => 'ProfilPageController',
        ]);
    }
}
