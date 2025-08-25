<?php
namespace App\Enum;

enum NewsCategoryEnum: string {
    case Aktualnosci = 'aktualnosci';
    case Technologie = 'technologie';
    case Artykuly = 'artykuly';

    public function id(): int
    {
        return match ($this) {
            self::Aktualnosci => 1,
            self::Technologie => 2,
            self::Artykuly => 3,
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::Aktualnosci => "Aktualności",
            self::Technologie => "Technologie",
            self::Artykuly => "Artykuły",
            default => ""
        };
    }
    
    public static function fromSlug(string $slug): ?self
    {
        return match($slug) {
            'aktualnosci' => self::Aktualnosci,
            'technologie' => self::Technologie,
            'artykuly' => self::Artykuly,
            default => null
        };
    }

    public static function fromId(int $id): ?self
    {
        return match($id) {
            1 => self::Aktualnosci,
            2 => self::Technologie,
            3 => self::Artykuly,
            default => null
        };
    }    
}
