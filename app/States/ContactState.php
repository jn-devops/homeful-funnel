<?php

namespace App\States;

use Spatie\ModelStates\Exceptions\InvalidConfig;
use Spatie\ModelStates\StateConfig;
use Spatie\ModelStates\State;

abstract class ContactState extends State
{
    /**
     * @throws InvalidConfig
     */
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Registered::class)
            ->allowTransition(Registered::class, Availed::class)
            ->allowTransition(Registered::class, Undecided::class)
            ->allowTransition(Registered::class, ForTripping::class)
            ->allowTransition(ForTripping::class, TrippingAssigned::class)
            ->allowTransition(TrippingAssigned::class, TrippingConfirmed::class)
            ->allowTransition(TrippingConfirmed::class, TrippingCompleted::class)
            ->allowTransition(TrippingCompleted::class, Availed::class)
            ;
    }
}
