<?php

namespace App\IgdbBundle\Mapper;

use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\Platform;
use App\Entity\Company;
use App\Entity\GameMode;
use stdClass;

class GameMapper
{
    public static function map(array $input, \Doctrine\ORM\EntityManager $em): Game
    {
        $Game = new Game();

        $Game->setId($input['id']);
        $Game->setName($input['name']);
        $Game->setFirstReleaseDate($input['first_release_date'] ?? 0);
        $Game->setStatus($input['status'] ?? '');
        $Game->setStoryline($input['storyline'] ?? '');
        $Game->setSummary($input['summary'] ?? '');
        $Game->setVersionTitle($input['version_title'] ?? '');
        $Game->setAggregatedRating($input['aggregated_rating'] ?? 0.0);
        $Game->setAggregatedRatingCount($input['aggregated_rating_count'] ?? 0);
        $Game->setFollows($input['follows'] ?? null);

        if (array_key_exists('genres', $input)) {
            foreach ($input['genres'] as $genreId) {

                $genre = $em->getRepository(Genre::class)->find($genreId);
                if ($genre != null) {
                    $Game->addGenre($genre);
                }


            }
        }

        if(array_key_exists('involved_companies', $input)){
            foreach ($input['involved_companies'] as $companyId) {

                $company = $em->getRepository(Company::class)->find($companyId);
                if ($company != null) {
                    $Game->addInvolvedCompany($company);
                }
            }
        }
        

        if(array_key_exists('platforms', $input)) {
            foreach ($input['platforms'] as $platformId) {

                
                $platform = $em->getRepository(Platform::class)->find($platformId);
                if ($platform != null) {
                    $Game->addPlatform($platform);
                }
            }
        }

        if(array_key_exists('game_modes', $input)) {
            foreach ($input['game_modes'] as $modeId) {

                $mode = $em->getRepository(GameMode::class)->find($modeId);

                if ($mode != null) {
                    $Game->addMode($mode);
                }

            }
        }
        return $Game;
    }

}
