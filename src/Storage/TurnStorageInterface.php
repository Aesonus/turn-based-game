<?php

namespace Aesonus\TurnGame\Storage;

use Aesonus\TurnGame\Contracts\PlayerInterface;
use Iterator;
use RuntimeException;
use SplStack;
use Throwable;

/**
 * Stores information about this turn
 * @author Aesonus <corylcomposinger at gmail.com>
 */
interface TurnStorageInterface
{
    /**
     * MUST set the turn's action. MUST return if the database save was successful.
     * @param Iterator|array $action_stack
     * @return bool
     */
    public function setActionStack($action_stack): bool;

    /**
     * MUST get the action stack from the database, or create it if it doesn't exist.
     * @return SplStack
     */
    public function getActionStack(): SplStack;

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

    /**
     * MUST set the id that this turn references in the database.
     * MUST throw exception if the id has already been set.
     * @param int $id
     * @return bool MUST return if the id exists
     * @throws RuntimeException
     */
    public function setTurnId(int $id): bool;

    /**
     * MUST save the turn to the database and return the turn id
     * @return ?int
     * @throws Throwable MUST throw an exception if the save fails
     */
    public function saveTurn(): ?int;

    /**
     *
     * @return TurnStorageInterface MUST return a new instance of the current class
     * with all dependencies injected
     */
    public static function newInstance();
}
