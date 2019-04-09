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
 *
 * @property \SplStack $action_stack The current actions to be executed
 * @property PlayerInterface $current_player The player who's "turn" it is
 */
interface TurnStorageInterface extends BaseStorageInterface
{
    /**
     * MUST set the turn's action. MUST return if the database save was successful.
     * @param Iterator|array $action_stack
     * @return bool
     */
    public function __setActionStack($action_stack): bool;

    /**
     * MUST get the action stack from the database, or create it if it doesn't exist.
     * @return SplStack
     */
    public function __getActionStack(): SplStack;

    /**
     * MUST set the turn's player. MUST return if the database save was successful.
     * @param PlayerInterface $player
     * @return bool
     */
    public function __setCurrentPlayer(PlayerInterface $player): bool;

    /**
     * MUST get the turn's player from the database or null if not set.
     * @return PlayerInterface|null
     */
    public function __getCurrentPlayer(): ?PlayerInterface;

    /**
     *
     * @return TurnStorageInterface MUST return a new instance of the current class
     * with all dependencies injected
     */
    public static function newInstance();
}
