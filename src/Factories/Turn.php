<?php

namespace Aesonus\TurnGame\Factories;

use Aesonus\TurnGame\Contracts\TurnInterface;

/**
 * Description of Turn
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class Turn implements \Aesonus\TurnGame\Contracts\TurnFactoryInterface
{
    /**
     * Create a new turn 
     * @param PlayerInterface $player
     * @return TurnInterface
     */
    public function newTurn(): TurnInterface
    {

    }
}
