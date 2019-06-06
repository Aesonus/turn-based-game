<?php

namespace Aesonus\TurnGame\Storage;

use Aesonus\TurnGame\Contracts\PlayerInterface;

/**
 * Stores information about this turn
 * @author Aesonus <corylcomposinger at gmail.com>
 *
 */
interface TurnStorageInterface extends BaseStorageInterface
{
    /**
     * MUST set the turn's action. MUST return if the database save was successful.
     * @param iterable $action_stack
     * @return bool
     */
    public function setActionStack(iterable $action_stack): bool;

    /**
     * MUST get the action stack from the database, or create it if it doesn't exist.
     * @return iterable
     */
    public function getActionStack(): iterable;

    /**
     * MUST set the turn's player. MUST return if the database save was successful.
     * @param PlayerInterface $player
     * @return bool
     */
    public function setCurrentPlayer(PlayerInterface $player): bool;

    /**
     * MUST get the turn's player from the database or null if not set.
     * @return PlayerInterface|null
     */
    public function getCurrentPlayer(): ?PlayerInterface;

}
