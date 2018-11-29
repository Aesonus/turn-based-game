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

use Aesonus\TurnGame\Contracts\ActionInterface;

/**
 * Used to trigger a response to an action
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class CounterActionException extends GameException
{
    /**
     *
     * @var ActionInterfacce
     */
    protected $action;
    
    /**
     * Sets the action this event is in response to
     * @param ActionInterface $action
     * @return void
     */
    public function setAction(ActionInterface $action): void
    {
        $this->action = $action;
    }
    
    /**
     * Gets the action this event is in response to
     * @return ActionInterface|null
     */
    public function getAction(): ?ActionInterface
    {
        return $this->action;
    }
}
