<?php

namespace App\IgdbBundle\Mapper;

use App\Entity\Company;
use stdClass;

class CompanyMapper
{
    public static function map(array $input, \Doctrine\ORM\EntityManager $em): ?Company
    {
        if ($em->getRepository(Company::class)->find($input['id'])) {

            return null;
        }

        $Company = new Company();
        $Company->setId($input['id']);
        $Company->setName($input['name']);
        $Company->setCountry($input['country'] ?? null);
        $Company->setDescription($input['description'] ?? null);

        return $Company;
    }
}