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

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCat(): int
    {
        return $this->cat;
    }
    
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setDescription(?string $description): self
    {
        $this->description = is_null($description) ? "" : $description;

        return $this;
    }

    public function setCat(int $cat): self
    {
        $this->cat = $cat;
        return $this;
    }
    

    //Helpers
    public function getCategoryName(): string
    {
        return NewsCategoryEnum::fromId($this->cat)->name();
    }
    
    public static function getCategoryNames(): array
    {
        $result = [];
        foreach (NewsCategoryEnum::cases() as $case) {
            $result[$case->id()] = $case->name();
        }
        return $result;
    }
}
?>