<?php

namespace Aesonus\TurnGame\Contracts;

/**
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
interface GameInterface
{
    /**
     * MUST add players to the game. MUST sort players in order of initiative
     * @param array|\ArrayAccess $players
     * @return $this MUST be fluent
     */
    public function setPlayers($players);
    
    /**
     * MUST return the players in this game that matches each of $player_names
     * @param string|array $player_names MUST be string or array of strings
     * @return array|null
     */
    public function findPlayers($player_names): ?array;
    
    /**
     * MUST sort players to a turn order
     * @return void
     */
    public function sortPlayers(): void;
    
    /**
     * MUST create a new turn. MUST set the player for the turn to the next 
     * player in the list after the current turn's player and wrap around to the
     * top of the list when the end is reached.
     * @return TurnInterface
     */
    public function newTurn(): TurnInterface;
    
    /**
     * MUST set the current turn to $turn
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
     * MUST return the next player in the turn order
     */
    public function nextPlayer(): TurnInterface;

    /**
     * MUST return true if the game has turns or false if not
     * @return boolean
     */
    public function isStarted(): bool;
}
