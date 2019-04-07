<?php

namespace Aesonus\TurnGame;

use Aesonus\PhpMagic\HasMagicProperties;
use Aesonus\TurnGame\Contracts\ActionInterface;
use Aesonus\TurnGame\Contracts\PlayerInterface;
use ArrayAccess;
use InvalidArgumentException;
use Iterator;

/**
 * Base class for actions
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
abstract class AbstractAction implements ActionInterface, ArrayAccess, Iterator
{
    use HasMagicProperties;
    use \Aesonus\PhpMagic\ImplementsMagicMethods;
    /**
     *
     * @var ActionInterface
     */
    protected $modified_actions;

    /**
     *
     * @var PlayerInterface[]
     */
    protected $targets;

    /**
     *
     * @var PlayerInterface
     */
    protected $player;

    /**
     * The type of this action
     * @var mixed
     */
    protected $type;

    /**
     *
     * @var bool
     */
    protected $resolved = false;

    /**
     *
     * @var Effects[]
     */
    protected $effects = [];

    /**
     *
     * @var int
     */
    protected $current_effect = 0;

    public function modifies($action): ActionInterface
    {
        $this->modified_actions = $action;
        return $this;
    }

    public function getModifiedAction(): ? ActionInterface
    {
        return $this->modified_actions;
    }

    public function targets($targets): ActionInterface
    {
        $targets = is_array($targets) ? $targets : [$targets];
        array_map(function ($target) {
            if (!$target instanceof PlayerInterface) {
                throw new InvalidArgumentException();
            }
        }, $targets);
        $this->targets = $targets;

        return $this;
    }

    public function getTargets(): ?array
    {
        return $this->targets;
    }

    public function player(): ?PlayerInterface
    {
        return $this->player;
    }

    public function setPlayer(PlayerInterface $player): ActionInterface
    {
        $this->player = $player;
        return $this;
    }

    public function setType($action_type): ActionInterface
    {
        $this->type = $action_type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setIsResolved(bool $resolved): ActionInterface
    {
        $this->resolved = $resolved;
        return $this;
    }

    public function isResolved(): bool
    {
        return $this->resolved;
    }

    ///Array Access Methods
    public function offsetExists($offset): bool
    {
        return (isset($this->effects[$offset]));
    }

    public function offsetGet($offset)
    {
        return $this->effects[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        if ($offset === null) {
            $offset = count($this->effects);
        }
        $this->effects[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->effects[$offset]);
    }

    ///Iterator Methods
    public function current()
    {
        return $this[$this->current_effect];
    }

    public function key()
    {
        return $this->current_effect;
    }

    public function next(): void
    {
        $this->current_effect ++;
    }

    public function rewind(): void
    {
        $this->current_effect = 0;
    }

    public function valid(): bool
    {
        return isset($this[$this->current_effect]);
    }
}
