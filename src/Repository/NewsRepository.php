<?php
// src/Repository/NewsRepository.php

namespace App\Repository;

use App\Entity\News;
use App\Repository\AbstractEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class NewsRepository extends AbstractEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }
}