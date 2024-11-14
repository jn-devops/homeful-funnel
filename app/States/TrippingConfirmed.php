<?php

namespace App\States;

class TrippingConfirmed extends TrippingState
{
    public function name(): string
    {
        return 'Contacted';
    }

    public static function label(): string
    {
        return 'Tripping Contacted';
    }
}
