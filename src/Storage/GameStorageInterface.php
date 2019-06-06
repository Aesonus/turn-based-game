<?php

namespace Aesonus\TurnGame\Storage;

use Aesonus\TurnGame\Contracts\TurnInterface;

/**
 *
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
interface GameStorageInterface extends BaseStorageInterface
{
    public function setPlayers(array $players): bool;

    public function getPlayers(): array;

    public function setTurns(array $turns): bool;

    public function pushTurn(TurnInterface $turn): bool;
}
