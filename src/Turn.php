<?php

namespace Aesonus\TurnGame;

use Aesonus\TurnGame\Contracts\ActionInterface;
use Aesonus\TurnGame\Contracts\PlayerInterface;
use Aesonus\TurnGame\Contracts\TurnInterface;
use Aesonus\TurnGame\Storage\TurnStorageInterface;
use OutOfRangeException;
use RuntimeException;
use SplStack;

/**
 * Base turn class
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class Turn implements TurnInterface
{

    /**
     *
     * @var TurnStorageInterface
     */
    protected $turn_storage;

    public function __construct(TurnStorageInterface $turn_storage)
    {
        $this->turn_storage = $turn_storage;
    }

    public function currentAction(): ?ActionInterface
    {
        try {
            return $this->turn_storage->getActionStack()[0];
        } catch (OutOfRangeException $exc) {
            return null;
        }
    }

    public function player(): ?PlayerInterface
    {
        return $this->turn_storage->getCurrentPlayer();
    }

    public function popAction(): ?ActionInterface
    {
        try {
            return $this->turn_storage->getActionStack()->pop();
        } catch (RuntimeException $exc) {
            return null;
        }
    }

    public function pushAction(ActionInterface $action): void
    {
        $this->turn_storage->getActionStack()->push($action);
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
        $this->turn_storage->setCurrentPlayer($player);
    }

    public function allActions()
    {
        return $this->turn_storage->getActionStack();
    }
}
