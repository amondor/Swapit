<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AddGameToListType;
use App\Repository\GameRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;

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

            $this->addFlash('red', 'Livre créé.');

            return $this->redirectToRoute("userwishgameslists");
        }

        return $this->render('front/user/user_own_games_list.html.twig', [
            'form' => $form->createView()
        ]);


    }

    /**
     * @Route("/userwishgameslists", name="userwishgameslists")
     */
    public function userWishGamesLists(GameRepository $gameRepository){

        $games = $gameRepository->findAllNames();

        $form = $this->createForm(AddGameToListType::class);
        /* $form->handleRequest(); */


        $gameMapped = [];

        foreach ($games as $game){
            $gameMapped[$game] = $game;
        }

        $form->add('wish', ChoiceType::class, [
                'multiple' => 'multiple',
                'method' => "post" ,
                'action' => "",
                'attr' => [
                    'class' => 'form-control js-example-basic-multiple',
                    'placeholder' => 'Jeux',
                    'name' => "states[]"
                ],
                'choices' => $gameMapped
        ]);

        return $this->render('front/user/user_wish_games_list.html.twig', [
            'form' => $form->createView()
        ]);


        $form->handleRequest($form);
        if ($form->isSubmitted() && $form->isValid()) {
            /* $em = $this->getDoctrine()->getManager();
            $em->persist($);
            $em->flush();

            $this->addFlash('red', 'Livre créé.');

            return $this->redirectToRoute('back_book_show', ['slug' => $book->getSlug()]); */
            /* dd($own); */
            return $this->redirectToRoute("home");
        }

    }

    public function autocomplete()
    {
        $search = strip_tags(trim($_POST['term']));

        return true;
    }

}
