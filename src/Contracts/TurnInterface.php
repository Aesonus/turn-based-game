<?php

namespace Aesonus\TurnGame\Contracts;

/**
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
interface TurnInterface
{
    /**
     * MUST set this turn's player to $player. MUST return $this
     * @param PlayerInterface $player
     * @return void
     */
    public function setPlayer(PlayerInterface $player): void;
    
    /**
     * MUST get the attached player or null if none are attached
     * @return PlayerInterface|null
     */
    public function player(): ?PlayerInterface;
    
    /**
     * MUST push the ActionInterface onto the action stack for this turn
     * @return void 
     */
    public function pushAction(ActionInterface $action): void;
    
    /**
     * MUST return the next action in the stack without popping it or resolving it.
     * @return ActionInterface|null MUST return null if there is no next action
     */
    public function currentAction(): ?ActionInterface;
    
    /**
     * MUST return pop the next action without resolving it.
     * @return ActionInterface|null MUST return null if there is no next action
     */
    public function popAction(): ?ActionInterface;
    
    /**
     * MUST Attempt to resolve the next action on the stack and pop it if exception
     * is not thrown.
     * @throws GameException SHOULD be thrown for game events only.
     * @return ActionInterface|null MUST return the action that was resolved or null
     * if no actions on stack
     */
    public function resolveNextAction(): ?ActionInterface;
}
