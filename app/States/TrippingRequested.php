<?php

namespace App\States;

class TrippingRequested extends TrippingState
{
    public function name(): string
    {
        return 'Requested';
    }

    public static function label(): string
    {
        return 'Tripping Requested';
    }
}
