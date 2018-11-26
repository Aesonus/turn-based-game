<?php

namespace Aesonus\TurnGame;

use Aesonus\TurnGame\Contracts\{
    GameInterface,
    TurnInterface,
    TurnFactoryInterface,
    ActionFactoryInterface
};

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
     * Contains all turns in the game with the first one being the current
     * @var TurnInterface[]
     */
    protected $turns;

    public function __construct(
        TurnFactoryInterface $turn_factory,
        ActionFactoryInterface $action_factory
        )
    {
        $this->turn_factory = $turn_factory;
        $this->action_factory = $action_factory;
    }

    public function currentTurn(): ?Contracts\TurnInterface
    {
        if (!isset($this->turns) || empty($this->turns)) {
            return null;
        }
        return $this->turns[0];
    }

    public function findPlayers($player_names): ?array
    {
        if (empty($this->players)) {
            return null;
        }
        $found = [];
        foreach ($this->players as $player) {
            if (in_array($player->name(), $player_names)) {
                $found[] = $player;
            }
        }
        return $found;
    }

    public function findTurn(int $index): ?Contracts\TurnInterface
    {
        return array_key_exists($index, $this->turns) ? $this->turns[$index] : null;
    }

    public function isStarted(): bool
    {
        return !empty($this->turns);
    }

    public function newTurn(): ?Contracts\TurnInterface
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

    public function nextPlayer(): ?Contracts\PlayerInterface
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

    public function setCurrentTurn(Contracts\TurnInterface $turn): void
    {
        if (!isset($this->turns)) {
            $this->turns[0] = $turn;
            return;
        }
        array_prepend($this->turns, $turn);
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
