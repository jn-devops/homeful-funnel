<?php

namespace App\States;

class TrippingAssigned extends TrippingState
{
    public function name(): string
    {
        return 'Assigned';
    }
    public static function label(): string
    {
        return 'Tripping Assigned';
    }
}
