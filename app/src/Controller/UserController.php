<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AddGameToListType;
use App\Repository\GameRepository;
use App\Services\Igdb;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    /**
     * @Route("/user_profile", name="user_profile")
     */
    public function index(Igdb $igdb)
    {
        $ownGames = $this->getUser()->getOwnGames();
        $wishGames = $this->getUser()->getWishGames();
        
        $waitingExchanges = [];
        $confirmedExchanges = [];

        foreach($this->getUser()->getExchanges() as $exchange){
            if($exchange->getConfirmed() === true){
                array_push($confirmedExchanges, $exchange);
            }
            elseif($exchange->getConfirmed() === null){
                array_push($waitingExchanges, $exchange);
            }
        }

        $arrayWishGames = [];
        $arrayOwnGames = [];
        
        foreach ($ownGames as $ownGame) {
            array_push($arrayOwnGames, $ownGame);
        }

        foreach ($wishGames as $wishGame) {
            array_push($arrayWishGames, $wishGame);
        }

        return $this->render('front/user/user_profile.html.twig', [
            'controller_name' => 'UserController',
            'ownGames' => $arrayOwnGames,
            'wishGames' => $arrayWishGames,
            'confirmedExchanges' => $confirmedExchanges,
            'waitingExchanges' => $waitingExchanges,
            'igdb' => $igdb
        ]);
    }

    /**
     * @Route("/userowngameslists", name="userowngameslists")
     */
    public function userOwnGamesLists(Request $request){

        $form = $this->createForm(AddGameToListType::class);

        $form->add('OwnGames', EntityType::class, [
                'class' => Game::class,
                'multiple' => 'multiple',
                'method' => "post" ,
                'action' => "",
                'attr' => [
                    'class' => 'form-control js-example-basic-multiple',
                    'placeholder' => 'Jeux',
                    'name' => "states[]"
                ],
                'choice_label' => 'name'
        ]);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request->get($form->getName());
            $dataOwnGames = $data['OwnGames'];
            $user = $this->getUser();
            foreach ($dataOwnGames as $ownGame){
                $game = $this->getDoctrine()->getRepository(Game::class)->find($ownGame);
                $user->addOwnGame($game);
            } 
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Jeux ajoutés !');
        }

        return $this->render('front/user/user_own_games_list.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/userwishgameslists", name="userwishgameslists")
     */
    public function userWishGamesLists(Request $request){

        $form = $this->createForm(AddGameToListType::class);

        $form->add('WishGames', EntityType::class, [
                'class' => Game::class,
                'multiple' => 'multiple',
                'method' => "post" ,
                'action' => "",
                'attr' => [
                    'class' => 'form-control js-example-basic-multiple',
                    'placeholder' => 'Jeux',
                    'name' => "states[]"
                ],
                'choice_label' => 'name'
        ]);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request->get($form->getName());
            $dataWishGames = $data['WishGames'];
            $user = $this->getUser();
            foreach ($dataWishGames as $wishGame){
                $game = $this->getDoctrine()->getRepository(Game::class)->find($wishGame);
                $user->addWishGame($game);
            } 
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Jeux ajoutés !');
        }

        return $this->render('front/user/user_wish_games_list.html.twig', [
            'form' => $form->createView()
        ]);
    }

    
}
