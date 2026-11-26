<?php

namespace App\Enums;

enum TourDifficulty: string
{
    case Easy = 'easy';
    case Medium = 'medium';
    case Hard = 'hard';

    public function level(): int
    {
        return match ($this) {
            self::Easy => 1,
            self::Medium => 2,
            self::Hard => 3,
        };
    }
}
