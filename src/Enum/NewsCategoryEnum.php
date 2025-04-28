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
    
    public static function fromSlug(string $slug): ?self
    {
        return match($slug) {
            'aktualnosci' => self::Aktualnosci,
            'technologie' => self::Technologie,
            'artykuly' => self::Artykuly,
            default => null
        };
    }
}
