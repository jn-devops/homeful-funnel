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
            ->default(FirstState::class)
            ->allowTransition(FirstState::class, SecondState::class)
            ->allowTransition(SecondState::class, ThirdState::class)
            ->allowTransition(ThirdState::class, FourthState::class)
            ;
    }
}
