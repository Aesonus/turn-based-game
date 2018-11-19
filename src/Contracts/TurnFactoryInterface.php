<?php

namespace Aesonus\TurnGame\Contracts;

/**
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
interface TurnFactoryInterface
{
    /**
     * MUST create and return a new turn.
     * @return TurnInterface
     */
    public function newTurn(): TurnInterface;
}
