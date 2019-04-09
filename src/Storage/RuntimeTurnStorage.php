<?php

namespace Aesonus\TurnGame\Storage;

use Aesonus\PhpMagic\HasInheritedMagicProperties;
use Aesonus\PhpMagic\ImplementsMagicMethods;
use Aesonus\TurnGame\Contracts\PlayerInterface;
use SplStack;

/**
 * Stores information about a turn in object properties.
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class RuntimeTurnStorage implements TurnStorageInterface
{
    use HasInheritedMagicProperties;
    use ImplementsMagicMethods;
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

    /**
     *
     * @var SplStack[]
     */
    protected static $action_stacks = [];

    /**
     *
     * @var PlayerInterface[]
     */
    protected static $current_players = [];

    /**
     *
     * @var int
     */
    protected $id;

    public function __getActionStack(): SplStack
    {
        if (!isset($this->action_stack)) {
            $this->__setActionStack(new \SplStack);
        }
        return $this->action_stack;
    }

    public function __getCurrentPlayer(): ?PlayerInterface
    {
        return $this->current_player;
    }

    public function __setActionStack($action_stack): bool
    {
        $this->action_stack = $action_stack;
        return true;
    }

    public function __setCurrentPlayer(PlayerInterface $player): bool
    {
        $this->current_player = $player;
        return true;
    }

    public function save(): ?int
    {
        if (!$this->id) {
            //Create new record in static variable
            $this->id = count(static::$current_players);
        }
        static::$current_players[$this->id] = $this->current_player;
        static::$action_stacks[$this->id] = $this->action_stack;
        return $this->id;
    }

    public function loadRecord($id): bool
    {
        if (!key_exists($id, static::$action_stacks)) {
            return false;
        }
        $this->id = $id;
        $this->refresh();
        return true;
    }

    protected function refresh()
    {
        if (!isset($this->id)) {
            return ;
        }
        $this->action_stack = static::$action_stacks[$this->id];
        $this->current_player = static::$current_players[$this->id];
    }

    public static function newInstance(): TurnStorageInterface
    {
        return new self;
    }

    public function deleteAllTurns(): void
    {
        static::$action_stacks = [];
        static::$current_players = [];
    }
}
