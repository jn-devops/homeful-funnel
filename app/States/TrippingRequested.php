<?php

namespace App\States;

class TrippingRequested extends TrippingState
{
    public function name(): string
    {
        return 'Tripping Requested';
    }

    public static function label(): string
    {
        return 'Tripping Requested';
    }
}
