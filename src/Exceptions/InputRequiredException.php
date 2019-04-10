<?php

namespace Aesonus\TurnGame\Exceptions;

use Aesonus\TurnGame\Contracts\PlayerInterface;
use Exception;
use Throwable;

/**
 * Thrown when the player must provide additional input
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
abstract class InputRequiredException extends GameException
{

    public function __construct(PlayerInterface $player, string $message = "", int $code = 0, Throwable $previous = NULL)
    {
        $this->setPlayer($player);
        return parent::__construct($message, $code, $previous);
    }
}
