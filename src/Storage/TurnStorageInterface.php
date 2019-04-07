<?php

namespace Aesonus\TurnGame\Storage;

use Aesonus\TurnGame\Contracts\PlayerInterface;
use SplStack;

/**
 * Stores information about this turn
 * @author Aesonus <corylcomposinger at gmail.com>
 */
interface TurnStorageInterface
{
    public function setActionStack($action_stack): bool;

    public function getActionStack(): SplStack;

    public function setCurrentPlayer(PlayerInterface $player): bool;

    public function getCurrentPlayer(): ?PlayerInterface;
}
