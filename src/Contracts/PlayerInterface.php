<?php

namespace Aesonus\TurnGame\Contracts;


/**
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
interface PlayerInterface
{
    
    /**
     * MUST return the unique name of the character
     */
    public function __toString();
    
    /**
     * MUST return whether the player can take an new action in response to $action
     * @param ActionInterface $action
     * @return bool|null
     */
    public function canTakeCounterAction(ActionInterface $action): ?bool;
    
    /**
     * MUST return a new action interface
     * @return \Aesonus\TurnGame\Contracts\ActionInterface
     */
    public function newAction(): ActionInterface;
    
    /**
     * MUST set the initiative of the player.
     * @param float $initiative
     * @return void
     */
    public function initiative(float $initiative): void;
    
    /**
     * MUST return the initiative of the player.
     * @return float
     */
    public function getInitiative(): float;
}
