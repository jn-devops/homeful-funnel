<?php

namespace App\States;

class TrippingAssigned extends TrippingState
{
    public function name(): string
    {
        return 'Tripping Assigned';
    }
    public static function label(): string
    {
        return 'Tripping Assigned';
    }
}
