<?php

namespace App\IgdbBundle\Mapper;

use App\Entity\Genre;
use stdClass;

class GenreMapper
{
    public static function map(array $input, \Doctrine\ORM\EntityManager $em): ?Genre
    {
        
        if ($em->getRepository(Genre::class)->find($input['id'])) {

            return null;
        }

        $Genre = new Genre();

        $Genre->setId($input['id']);
        $Genre->setName($input['name']);
        $Genre->setSlug($input['slug'] ?? null);

        return $Genre;
    }

}
