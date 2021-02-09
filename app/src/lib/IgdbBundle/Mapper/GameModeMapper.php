<?php

namespace App\IgdbBundle\Mapper;

use App\Entity\GameMode;
use Doctrine\ORM\EntityManagerInterface;
use stdClass;

class GameModeMapper
{
    public static function map(array $input, \Doctrine\ORM\EntityManager $em): ?GameMode
    {

        if ($em->getRepository(GameMode::class)->find($input['id'])) {

            return null;
        }

        $GameMode = new GameMode();

        $GameMode->setId($input['id']);
        $GameMode->setName($input['name']);
        $GameMode->setSlug($input['slug'] ?? null);
        $GameMode->setUrl($input['url'] ?? null);

        return $GameMode;
    }
}
