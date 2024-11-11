<?php

namespace App\States;

class TrippingCompleted extends TrippingState
{
    public function name(): string
    {
        return 'Tripping Completed';
    }

    public static function label(): string
    {
        return 'Tripping Completed';
    }
}
