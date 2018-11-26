<?php

namespace Aesonus\TurnGame\Contracts;

/**
 * Responsible for top level game functionality
 * @author Aesonus <corylcomposinger at gmail.com>
 */
interface GameInterface
{
    /**
     * MUST set players of the game. MUST sort players in order of initiative
     * @param PlayerInterface[] $players
     * @return void
     */
    public function setPlayers(array $players): void;
    
    /**
     * MUST return the players in this game that matches each of $player_names
     * @param string|array $player_names MUST be string or array of strings
     * @return PlayerInterface[]|null
     */
    public function findPlayers($player_names): ?array;
    
    /**
     * MUST return the next player in the turn order after the current turn's player
     * wrapping to the top of the rotation after MUST return the first player in the game to have a turn if there is no current turn.
     * @return PlayerInterface|null MUST return null if there are no players in the game
     */
    public function nextPlayer(): ?PlayerInterface;
    
    /**
     * MUST create a new turn. MUST set the player for the turn to the next 
     * player in the list after the current turn's player and wrap around to the
     * top of the list when the end is reached. MUST push at least one action
     * onto the turn's action stack. This action's player SHOULD be the created
     * turn's player.
     * @return TurnInterface|null MUST return null if there are no players in the game
     */
    public function newTurn(): ?TurnInterface;
    
    /**
     * MUST set the current turn to $turn. MUST preserve previous turns.
     * @param TurnInterface $turn
     * @return void
     */
    public function setCurrentTurn(TurnInterface $turn): void;
        
    /**
     * MUST return the current turn or null if game hasn't started
     * @return TurnInterface|null
     */
    public function currentTurn(): ?TurnInterface;
    
    /**
     * MUST find the turn by index. MUST return the current turn when $index = 0.
     * @param int $index
     * @return TurnInterface|null MUST return null if no turn is at $index
     */
    public function findTurn(int $index): ?TurnInterface;

    /**
     * MUST return true if the game has turns or false if not
     * @return boolean
     */
    public function isStarted(): bool;
}
