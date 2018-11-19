<?php

namespace Aesonus\TurnGame\Contracts;


/**
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
interface PlayerInterface
{
    
    /**
     * MUST return the name of the character
     */
    public function __toString();
    
    /**
     * MUST return whether the player can take an new action in response to $action
     * @param ActionInterface $action
     * @return bool|null
     */
    public function canTakeCounterAction(ActionInterface $action): ?bool;
    
    /**
     * MUST return a new action interface belonging to this player
     * @return ActionInterface
     */
    public function newAction(): ActionInterface;
    
    /**
     * MUST set the initiative of the player.
     * @param float $initiative
     * @return void
     */
    public function setInitiative(float $initiative): void;
    
    /**
     * MUST return the initiative of the player or null if it isn't set
     * @return float
     */
    public function initiative(): ?float;
    
    /**
     * MUST set the player's name
     * @param string $name
     * @return void
     */
    public function setName(string $name): void;
    
    /**
     * MUST return the name of the player or null if it isn't set
     * @return string|null
     */
    public function name(): ?string;
}
