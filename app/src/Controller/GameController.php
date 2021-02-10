<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use App\IgdbBundle\IgdbWrapper\IgdbWrapper;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GameController extends AbstractController
{
    /**
     * @Route("/games", name="games", methods={"GET"})
     */
    public function index(GameRepository $gameRepository, IgdbWrapper $igdb, Request $request, PaginatorInterface $paginator)
    {
        $gameSearched = $gameRepository->findGamePopular();

        $gamesData = $paginator->paginate(
            $gameSearched,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('front/games/games.html.twig', [
            'games' => $gamesData,
            'igdb' => $igdb
        ]);
    }

    /**
     * @Route("/result", name="search", methods={"GET", "POST"})
     */
    public function searchGameAction(Request $request, GameRepository $gameRepository, IgdbWrapper $igdb, PaginatorInterface $paginator){
        $search = $request->query->get('search');
        $gameSearched = $gameRepository->findGameByName($search.'%');

        $gamesData = $paginator->paginate(
            $gameSearched,
            $request->query->getInt('page', 1),
            15
        );

        return $this->render('front/games/search-games.html.twig', [
            'games' => $gamesData,
            'igdb' => $igdb
        ]);
    }

    // FEATURE EN COURS : POUR LE MOMENT C'EST BUGUE
    // /**
    //  * @Route("/result", name="search", methods={"GET", "POST"})
    //  */
    // public function searchGameFiltered(Request $request, GameRepository $gameRepository, Igdb $igdb, PaginatorInterface $paginator){
    //     $search['name'] = $request->request->get('search');

    //     if(!empty($request->request->get('genre'))){ $search['genre'] = $request->request->get('genre'); } 
    //     // (!$request->query->get('genre')) ? : $search['genre'] = $request->request->get('genre');

    //     $gameSearched = $gameRepository->search($search);

    //     $gamesData = $paginator->paginate(
    //         $gameSearched,
    //         $request->query->getInt('page', 1),
    //         15
    //     );

    //     return $this->render('front/games/search-games.html.twig', [
    //         'games' => $gamesData,
    //         'igdb' => $igdb
    //     ]);
    // }

    /**
     * @Route("/show/{id}", name="show", methods={"GET"})
     */
    public function show(Game $game, IgdbWrapper $igdb)
    {
        $owners = $game->getOwners();
        $arrayOwners = [];

        foreach($owners as $owner){
            array_push($arrayOwners, $owner);
        }

        return $this->render('front/games/show.html.twig', [
            'game' => $game,
            'igdb' => $igdb,
            'owners' => $arrayOwners
        ]);
    }


    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request)
    {
        $game = new Game();

        $form = $this->createForm(GameType::class, $game);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($game);
            $em->flush();

            return $this->redirectToRoute('game_index');
        }

        return $this->render('games/new.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET", "POST"})
     */
    public function edit(Game $game, Request $request)
    {
        $form = $this->createForm(GameType::class, $game);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('game_index');
        }

        return $this->render('game/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

     /**
     * @Route("/delete/{id}/{token}", name="delete")
     */
    public function delete(Game $game, $token)
    {
        if (!$this->isCsrfTokenValid('delete_game' . $game->getId(), $token)) {
            throw new Exception('Invalid token CSRF');
        }

            $em = $this->getDoctrine()->getManager();
            $em->remove($game);
            $em->flush();

            return $this->redirectToRoute('game_index');
    }
}
