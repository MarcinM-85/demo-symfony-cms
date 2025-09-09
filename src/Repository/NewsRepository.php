<?php
// src/Repository/NewsRepository.php

namespace App\Repository;

use App\Entity\News;
use App\Enum\NewsCategoryEnum;
use App\Repository\AbstractEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class NewsRepository extends AbstractEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }
    
    public function getList(array $params = [], string $alias = 'e'): array 
    {
        $elementList = parent::getList( $params, $alias );
        foreach( $elementList['items'] as &$e ){
            $e->enumSlug = NewsCategoryEnum::fromId($e->getCat());
//            dump($e);
        }

        return $elementList;
    }
}