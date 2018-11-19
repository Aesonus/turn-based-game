<?php

namespace Aesonus\TurnGame\Exceptions;

/**
 * Base Exception for the turn game. Used for wiring events into the system
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
abstract class GameException extends \RuntimeException
{
    /**
     * Sets the player that the event is thrown by
     * @param PlayerInterface $player
     * @return void
     */
    public function setPlayer(PlayerInterface $player): void
    {
        
    }

    public function getPlayer(): PlayerInterface
    {
        
    }
}
