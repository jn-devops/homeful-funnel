<?php

namespace App\States;

class TrippingCancelled extends TrippingState
{
    public function name(): string
    {
        return 'Cancelled';
    }

    public static function label(): string
    {
        return 'Tripping Cancelled';
    }

}
