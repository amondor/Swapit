<?php

namespace App\IgdbBundle\Mapper;

use App\Entity\Platform;

class PlatformMapper
{
    public static function map(array $input, \Doctrine\ORM\EntityManager $em): Platform
    {
        $Platform = new Platform();

        $Platform->setId($input['id']);
        $Platform->setName($input['name']);
        $Platform->setSlug($input['slug'] ?? null);
        $Platform->setUrl($input['url'] ?? null);

        return $Platform;
    }
}
