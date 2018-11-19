<?php
/*
 * This file is part of the Turn-game project.
 *
 * (c) Cory Laughlin <corylcomposinger@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aesonus\TurnGame\Exceptions;

use Aesonus\TurnGame\Contracts\{
    PlayerInterface,
    ActionInterface
};

/**
 * 
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class CounterActionException extends GameException
{
    /**
     * Sets the action this exception is in response to
     * @param ActionInterface $action
     * @return void
     */
    public function setAction(ActionInterface $action): void
    {
        
    }
    
    public function getAction(): ActionInterface
    {
        
    }
}
