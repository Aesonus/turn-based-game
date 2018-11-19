<?php

namespace Aesonus\TurnGame;

use Aesonus\TurnGame\Exceptions\InvalidActionException;
use Aesonus\TurnGame\Contracts\{
    ActionInterface,
    PlayerInterface,
    ActionStorageInterface
};

/**
 * Base class for actions
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
abstract class AbstractAction implements Contracts\ActionInterface
{
    /**
     * 
     * @var ActionStorageInterface
     */
    protected $storage;
    
    /**
     *
     * @var ActionInterface
     */
    protected $modified_action;
    
    /**
     *
     * @var array
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

    public function modifies(ActionInterface $action): ActionInterface
    {
        if ($this->getTargets() !== null) {
            throw new InvalidActionException();
        }
        $this->modified_action = $action;
        return clone $this;
    }
    
    public function getModifiedAction(): ?ActionInterface
    {
        return $this->modified_action;
    }

    public function targets($targets): ActionInterface
    {
        if ($this->getModifiedAction() !== null) {
            throw new InvalidActionException();
        }
        $targets = is_array($targets) ? $targets : [$targets];
        array_map(function ($target) {
            if (!$target instanceof PlayerInterface) {
                throw new \InvalidArgumentException();
            }
        }, $targets);
        $this->targets = $targets;
        
        return clone $this;
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
        return clone $this;
    }

    public function setType($action_type): ActionInterface
    {
        $this->type = $action_type;
        return clone $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function isResolved(): bool
    {
        return $this->resolved;
    }
}
