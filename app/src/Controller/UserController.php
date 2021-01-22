<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user_profile", name="user_profile")
     */
    public function index()
    {
        return $this->render('front/user/user_profile.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
