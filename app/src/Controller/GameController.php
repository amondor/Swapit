<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\GameRepository;
use App\Form\GameType;
use App\Entity\Game;


/**
 * Class GameController
 * @Route("/game", name="game_")
 */

class GameController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(GameRepository $gameRepository)
    {
        return $this->render('game/index.html.twig', [
            'games' => $gameRepository->findAll()
        ]);
    }

    /**
     * @Route("/show/{id}", name="show", methods={"GET"})
     */
    public function show(Game $game)
    {
            return $this->render('game/show.html.twig', [
                'game' => $game
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

        return $this->render('game/new.html.twig', [
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
