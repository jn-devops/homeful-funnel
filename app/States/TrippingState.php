<?php

namespace App\States;

use Spatie\ModelStates\Exceptions\InvalidConfig;
use Spatie\ModelStates\StateConfig;
use Spatie\ModelStates\State;

abstract class TrippingState extends State
{
    public const STATES = [
        ForTripping::class,
        TrippingAssigned::class,
        TrippingConfirmed::class,
        TrippingCompleted::class,
        TrippingCancelled::class,
    ];
    /**
     * @throws InvalidConfig
     */
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(ForTripping::class)
            ->allowTransition(ForTripping::class, TrippingAssigned::class)
            ->allowTransition(TrippingAssigned::class, TrippingConfirmed::class)
            ->allowTransition(TrippingAssigned::class, TrippingCancelled::class)
            ->allowTransition(TrippingConfirmed::class, TrippingCompleted::class)
            ->allowTransition(TrippingConfirmed::class, TrippingCancelled::class)
            ;
    }
}
