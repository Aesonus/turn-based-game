<?php

namespace Aesonus\TurnGame;

use Aesonus\TurnGame\Contracts\PlayerInterface;
use Aesonus\TurnGame\Contracts\ActionInterface;
use Aesonus\TurnGame\Contracts\TurnInterface;

/**
 * Base turn class
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class Turn implements TurnInterface
{

    /**
     *
     * @var \SplStack
     */
    protected $action_stack;

    /**
     *
     * @var PlayerInterface
     */
    protected $player;

    public function __construct(\SplStack $action_stack)
    {
        $this->action_stack = $action_stack;
    }

    public function currentAction(): ?ActionInterface
    {
        try {
            return $this->action_stack[0];
        } catch (\OutOfRangeException $exc) {
            return null;
        }
    }

    public function player(): ?PlayerInterface
    {
        return $this->player;
    }

    public function popAction(): ?ActionInterface
    {
        try {
            return $this->action_stack->pop();
        } catch (\RuntimeException $exc) {
            return null;
        }
    }

    public function pushAction(ActionInterface $action): void
    {
        $this->action_stack->push($action);
    }

    public function resolveNextAction(): ?ActionInterface
    {
        if (!($action = $this->currentAction())) {
            return null;
        }
        $action = $action->resolve();
        $this->popAction();
        return $action;
    }

    public function setPlayer(PlayerInterface $player): void
    {
        $this->player = $player;
    }
}
