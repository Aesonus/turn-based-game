<?php

namespace Aesonus\TurnGame\Exceptions;

use Aesonus\TurnGame\Contracts\PlayerInterface;
use Exception;
use Throwable;

/**
 * Description of CurrentPlayerDartInputRequiredException
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class DartInputRequiredException extends InputRequiredException
{
    /**
     *
     * @var int
     */
    protected $number;

    public function __construct(PlayerInterface $player, int $number = 1, string $message = "", int $code = 0, Throwable $previous = NULL): Exception
    {
        $this->number = $number;
        return parent::__construct($player, $message, $code, $previous);
    }
}
