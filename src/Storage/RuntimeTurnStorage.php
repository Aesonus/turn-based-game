<?php

namespace Aesonus\TurnGame\Storage;

use Aesonus\TurnGame\Contracts\PlayerInterface;
use SplStack;

/**
 * Stores information about a turn in object properties.
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class RuntimeTurnStorage implements TurnStorageInterface
{
    /**
     *
     * @var SplStack
     */
    protected $action_stack;

    /**
     *
     * @var PlayerInterface
     */
    protected $current_player;

    public function getActionStack(): SplStack
    {
        if (!isset($this->action_stack)) {
            $this->setActionStack(new \SplStack);
        }
        return $this->action_stack;
    }

    public function getCurrentPlayer(): ?PlayerInterface
    {
        return $this->current_player;
    }

    public function setActionStack($action_stack): bool
    {
        $this->action_stack = $action_stack;
        return true;
    }

    public function setCurrentPlayer(PlayerInterface $player): bool
    {
        $this->current_player = $player;
        return true;
    }
}
