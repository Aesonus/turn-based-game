<?php

namespace Aesonus\TurnGame;

use Aesonus\TurnGame\Contracts\ActionFactoryInterface;
use Aesonus\TurnGame\Contracts\GameInterface;
use Aesonus\TurnGame\Contracts\PlayerInterface;
use Aesonus\TurnGame\Contracts\TurnFactoryInterface;
use Aesonus\TurnGame\Contracts\TurnInterface;

/**
 * Controls top level game functionality
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class Game implements GameInterface
{

    /**
     *
     * @var TurnFactoryInterface
     */
    protected $turn_factory;

    /**
     *
     * @var ActionFactoryInterface
     */
    protected $action_factory;

    /**
     *
     * @var array
     */
    protected $players = [];

    /**
     * Contains all turns in the game with the first one (element [0]) being the current
     * @var TurnInterface[]
     */
    protected $turns;

    public function __construct(
        TurnFactoryInterface $turn_factory,
        ActionFactoryInterface $action_factory
        ) {
        $this->turn_factory = $turn_factory;
        $this->action_factory = $action_factory;
    }

    public function currentTurn(): ?TurnInterface
    {
        if (!isset($this->turns) || empty($this->turns)) {
            return null;
        }
        return $this->turns[0];
    }

    public function findPlayers($player_names = null): ?array
    {
        if (empty($this->players)) {
            return null;
        }
        if (!isset($player_names)) {
            return $this->players;
        }
        $found = [];
        foreach ($this->players as $player) {
            if (in_array($player->name(), $player_names)) {
                $found[] = $player;
            }
        }
        return $found;
    }

    public function findTurn(int $index): ?TurnInterface
    {
        if (!isset($this->turns)) {
            return null;
        }
        return array_key_exists($index, $this->turns) ? $this->turns[$index] : null;
    }

    public function allTurns(): ?array
    {
        return $this->turns;
    }

    public function isStarted(): bool
    {
        return !empty($this->turns);
    }

    public function newTurn(): ?TurnInterface
    {
        if (!($next_player = $this->nextPlayer())) {
            return null;
        }
        $turn = $this->turn_factory->newTurn();
        $turn->setPlayer($next_player);
        $turn->pushAction($this->action_factory
            ->newAction()->setPlayer($next_player));
        return $turn;
    }

    public function nextPlayer(): ?PlayerInterface
    {
        if (empty($this->players)) {
            return null;
        }
        if (!($current_turn = $this->currentTurn())) {
            return $this->players[0];
        }
        $index = array_search($current_turn->player(), $this->players) + 1;
        return $index < count($this->players) ? $this->players[$index] :
            $this->players[0];
    }

    public function setCurrentTurn(TurnInterface $turn): void
    {
        if (!isset($this->turns)) {
            $this->turns[0] = $turn;
            return;
        }
        $this->turns = array_merge([$turn], $this->turns);
    }

    public function setPlayers(array $players): void
    {
        //First player in the first array element
        usort($players, function ($a, $b) {
            return (int) ($b->initiative() - $a->initiative()) * 2;
        });
        $this->players = $players;
    }
}
