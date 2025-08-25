<?php
declare(strict_types=1);

namespace App\Bundle\FileGator\Model;

interface UserInterface
{
    public function getFileGatorUsername(): string;

    public function getFileGatorName(): string;
    
    public function getFileGatorRole(): string;

    public function getFileGatorPermissions(): array;

    public function getFileGatorHomedir(bool $privateRepository): string;
}
