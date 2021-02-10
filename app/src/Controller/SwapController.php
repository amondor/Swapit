<?php

namespace App\Controller;

use App\Entity\Exchange;
use App\Entity\Game;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SwapController extends AbstractController
{
    /**
     * @Route("/swap_game/{id}/{id_game}", name="swap_game")
     */
    public function index(User $user, Game $id_game)
    {
        // fetching user games wish
        $userWishes = $user->getWishGames();
        $userWishers = [];
        
        foreach($userWishes as $userWish){
            array_push($userWishers, $userWish);
        }

        $userCurrentOwns = $this->getUser()->getOwnGames();
        $currentUserWishers = [];

        foreach($userCurrentOwns as $userCurrentOwn){
            array_push($currentUserWishers, $userCurrentOwn);
        }

        $matchingGames = [];
        $unmatchingGames = [];

        foreach($userWishers as $userWish){
            foreach($currentUserWishers as $key => $currentUserWisher){
                if($currentUserWisher->getId() == $userWish->getId()){
                    array_push($matchingGames, $currentUserWisher);
                }
            }
        }

        $currentUserGameIds = [];
        foreach($userCurrentOwns as $userCurrentOwn){
            array_push($currentUserGameIds, $userCurrentOwn->getId());
        }

        foreach($userWishes as $userCurrentOwn){
            if(!in_array($userCurrentOwn->getId(), $currentUserGameIds)){
                array_push($unmatchingGames, $userCurrentOwn);
            }
        }

        return $this->render('front/swap/swap_game.html.twig', [
            'userOwnerGame' => $id_game,
            'owner' => $user,
            'matchingGames' => $matchingGames,
            'unmatchingGames' => $unmatchingGames
        ]);
    }

    /**
     * @Route("/recap_game/{user}/{selected_game}/{game}/{owner}", name="recap_swap_game")
     */
    public function recap_swap_game(User $user, Game $selected_game, Game $game, User $owner){
        return $this->render('front/swap/recap_swap_game.html.twig', [
            'user' => $user,
            'owner' => $owner,
            'gameSelected' => $selected_game,
            'game' => $game,
            'currentUser' => $this->getUser()
        ]);
    }

    /**
     * @Route("/validate_swap/{user}/{selected_game}/{ownerGame}/{owner}", name="validate_swap")
     */
    public function validate_Swap(User $user, Game $selected_game, Game $ownerGame, User $owner, EntityManagerInterface $em, \Swift_Mailer $mailer){
        $exchange = new Exchange();
        
        $exchange->setUserProposer($this->getUser());
        $exchange->setUserOwner($user);
        $exchange->setGame($selected_game);
        $exchange->setOwnerGame($ownerGame);
        
        $em->persist($exchange);
        $em->flush();

        $messageProposerSender = (new \Swift_Message('Votre demande d\'échange a été envoyée !'))
            ->setFrom('swapit.esgi@gmail.com')
            ->setTo($this->getUser()->getEmail())
            ->setBody(
                $this->renderView(
                    'mail/sending_swap_proposal.html.twig',
                    [
                        'user' => $this->getUser(),
                        'owner' => $owner,
                        'ownerGame' => $ownerGame,
                        'selected_game' => $selected_game
                    ]
                    ),
                    'text/html'
                );

        $mailer->send($messageProposerSender);

        $messageProposerReceiver = (new \Swift_Message('Vous avez reçu une demande d\'échange !'))
            ->setFrom('swapit.esgi@gmail.com')
            ->setTo($owner->getEmail())
            ->setBody(
                $this->renderView(
                    'mail/receiving_swap_proposal.html.twig',
                    [
                        'exchange' => $exchange,
                        'user' => $this->getUser(),
                        'ownerGame' => $ownerGame,
                        'selected_game' => $selected_game,
                        'owner' => $owner
                    ]
                    ),
                    'text/html'
                );
                
        $mailer->send($messageProposerReceiver);

        return $this->redirectToRoute('games');
    }

    /**
     * @Route("/exchange_confirmed/{exchange}", name="exchange_confirmed")
     */
    public function validate_exchange(Exchange $exchange, \Swift_Mailer $mailer, EntityManagerInterface $em){
        if($exchange->getConfirmed() !== null){
            return $this->redirectToRoute('home');
        }
        $exchange->setConfirmed(true);
        $em->persist($exchange);
        $em->flush();

        $messageConfirmReceiver = (new \Swift_Message('Votre confirmation de swap a bien été prise en compte !'))
            ->setFrom('swapit.esgi@gmail.com')
            ->setTo($exchange->getUserOwner()->getEmail())
            ->setBody(
                $this->renderView(
                    'mail/swap_confirmation_to_owner.html.twig',
                    [
                        'exchange' => $exchange,
                        'user' => $exchange->getUserProposer(),
                        'ownerGame' => $exchange->getOwnerGame(),
                        'selected_game' => $exchange->getGame(),
                        'owner' => $exchange->getUserOwner()
                    ]
                    ),
                    'text/html'
                );
                
        $mailer->send($messageConfirmReceiver);

        $messageConfirmProposer = (new \Swift_Message('Votre demande de swap a bien été validée !'))
            ->setFrom('swapit.esgi@gmail.com')
            ->setTo($exchange->getUserProposer()->getEmail())
            ->setBody(
                $this->renderView(
                    'mail/swap_confirmation_to_proposer.html.twig',
                    [
                        'exchange' => $exchange,
                        'user' => $exchange->getUserProposer(),
                        'ownerGame' => $exchange->getOwnerGame(),
                        'selected_game' => $exchange->getGame(),
                        'owner' => $exchange->getUserOwner()
                    ]
                    ),
                    'text/html'
                );
                
        $mailer->send($messageConfirmProposer);

        return $this->render('front/swap/swap_confirmed.html.twig');
    }

    /**
     * @Route("/exchange_denied/{exchange}", name="exchange_denied")
     */
    public function exchange_denied(Exchange $exchange, \Swift_Mailer $mailer, EntityManagerInterface $em){
        if($exchange->getConfirmed() !== null){
            return $this->redirectToRoute('home');
        }
        $exchange->setConfirmed(false);
        $em->persist($exchange);
        $em->flush();

        $messageConfirmReceiver = (new \Swift_Message('Votre refus de swap a bien été prise en compte !'))
            ->setFrom('swapit.esgi@gmail.com')
            ->setTo($exchange->getUserOwner()->getEmail())
            ->setBody(
                $this->renderView(
                    'mail/swap_refused_to_owner.html.twig',
                    [
                        'exchange' => $exchange,
                        'user' => $exchange->getUserProposer(),
                        'ownerGame' => $exchange->getOwnerGame(),
                        'selected_game' => $exchange->getGame(),
                        'owner' => $exchange->getUserOwner()
                    ]
                    ),
                    'text/html'
                );
        $mailer->send($messageConfirmReceiver);

        $messageConfirmProposer = (new \Swift_Message('Votre demande de swap a été refusée ! :('))
            ->setFrom('swapit.esgi@gmail.com')
            ->setTo($exchange->getUserProposer()->getEmail())
            ->setBody(
                $this->renderView(
                    'mail/swap_refused_to_proposer.html.twig',
                    [
                        'exchange' => $exchange,
                        'user' => $exchange->getUserProposer(),
                        'ownerGame' => $exchange->getOwnerGame(),
                        'selected_game' => $exchange->getGame(),
                        'owner' => $exchange->getUserOwner()
                    ]
                    ),
                    'text/html'
                );
                
        $mailer->send($messageConfirmProposer);

        return $this->render('swap/swap_denied.html.twig');
    }
}
