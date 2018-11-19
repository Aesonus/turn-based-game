<?php

namespace Aesonus\TurnGame\Contracts;

use Aesonus\TurnGame\Exceptions\{
    GameException,
    InvalidActionException
};

/**
 * Defines an individual action.
 * MUST serialize and deserialize to json
 * @author Aesonus <corylcomposinger at gmail.com>
 */
interface ActionInterface extends \Serializable
{

    /**
     * MUST set the player taking this action (yes it can be on another player's
     * turn) 
     * @param PlayerInterface $player
     * @return ActionInterface MUST return a new ActionInterface
     */
    public function setPlayer(PlayerInterface $player);
    
    /**
     * MUST return the player taking this action.
     */
    public function player(): PlayerInterface;
    
    /**
     * MUST set the target(s) of this action. An action MUST exclusively target 
     * any player or modify an action.
     * @param PlayerInterface[]|PlayerInterface $targets MUST accept a PlayerInterface
     * or an array of PlayerInterfaces.
     * @return ActionInterface MUST return a new ActionInterface
     * @throws InvalidActionException MUST be thrown when the action already
     * has modifications
     * @throws \InvalidArgumentException MUST be thrown when $targets parameter is
     * not a PlayerInterface or an array of PlayerInterfaces
     */
    public function targets($targets): ActionInterface;
        
    /**
     * MUST return array of targets or null if no targets are set.
     * @return array|null
     */
    public function getTargets(): ?array;
    
    /**
     * MUST set which action is modified by this action.
     * @param ActionInterface $action MUST accept an ActionInterface
     * @throws InvalidActionException MUST be thrown when the action already
     * has targeted players
     * @return ActionInterface MUST return a new ActionInterface
     */
    public function modifies(ActionInterface $action): ActionInterface;
    
    /**
     * MUST return array of modified action or null if no targets are set.
     * @return array|null
     */
    public function getModifiedAction(): ?array;
    
    /**
     * MUST attempt to resolve this action against the targets or the modified action.
     * MUST resume resolution if called after a GameException was thrown during 
     * a previous execution of this method.
     * @throws GameException SHOULD be thrown for game events only
     * @throws InvalidActionException MUST throw when action has no modified action
     * or target
     * @return ActionInterface MUST return a new ActionInterface or null if the
     * action is already resolved
     */
    public function resolve(): ?ActionInterface;
    
    /**
     * MUST return if the action has been resolved
     * @return bool
     */
    public function isResolved(): bool;
    
    /**
     * MUST set the type of the action. Can be as simple as setting
     * a property on this ActionInterface to returning a different child of 
     * ActionInterface altogether
     * @param mixed $action_type
     * @return ActionInterface
     */
    public function setType($action_type): ActionInterface;
    
    /**
     * MUST return the type of action this is.
     * @return mixed
     */
    public function getType();
}