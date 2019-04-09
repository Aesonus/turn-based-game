<?php

namespace Aesonus\TurnGame\Exceptions;

use Aesonus\PhpMagic\HasInheritedMagicProperties;
use Aesonus\PhpMagic\ImplementsMagicMethods;
use Aesonus\TurnGame\Contracts\PlayerInterface;
use RuntimeException;

/**
 * Base Exception for the turn game. Used for wiring events into the system
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
abstract class GameException extends RuntimeException
{
    use HasInheritedMagicProperties;
    use ImplementsMagicMethods;

    /**
     *
     * @var PlayerInterface
     */
    protected $player;
    
    /**
     * Sets the player that the event is thrown by
     * @param PlayerInterface $player
     * @return void
     */
    public function setPlayer(PlayerInterface $player): void
    {
        $this->player = $player;
    }

    /**
     * Gets the player that the event was thrown by
     * @return PlayerInterface|null
     */
    public function getPlayer(): ?PlayerInterface
    {
        return $this->player;
    }

}
