<?php
// src/Entity/News.php

namespace App\Entity;

use App\Enum\NewsCategoryEnum;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'news')]
class News
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: 'integer', options: ["unsigned" => true])]
    private int $cat;

    public function __construct()
    {
        $this->cat = NewsCategoryEnum::Aktualnosci->id();
    }
    
    public function __get(string $name)
    {
        return $this->$name ?? null;
    }

    public function __set(string $name, $value): void
    {
        $this->$name = $value;
    }
}
?>