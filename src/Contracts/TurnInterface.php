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
     * @return $this
     */
    public function setPlayer(PlayerInterface $player): self;
    
    /**
     * MUST get the attached player or null if none are attached
     * @return PlayerInterface|null
     */
    public function player(): ?PlayerInterface;
    
    /**
     * MUST push the ActionInterface onto the action stack for this turn
     * @return $this MUST be fluent
     */
    public function pushAction(ActionInterface $action);
    
    /**
     * MUST return the next action without popping it or resolving it.
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
     * @return ActionInterface MUST return the action that was resolved.
     */
    public function resolveNextAction(): ActionInterface;
    
    /**
     * MUST return array of actions taken by the given player in the order taken
     * @param PlayerInterface $player
     */
    public function actionsTakenBy(PlayerInterface $player);

    /**
     * MUST return the last $offset action(s)
     * @deprecated since version 0
     * @param int $offset $offset of 1 SHOULD be the current action
     * @throws \InvalidArgumentException MUST be thrown if $offset is less than 1
     * @return ActionInterface|null MUST return the ActionInterface or 
     * null if none exist at offset
     */
    public function findAction($offset): ?ActionInterface;
}
