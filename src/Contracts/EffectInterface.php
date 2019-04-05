<?php

namespace Aesonus\TurnGame\Contracts;

use Aesonus\PhpMagic\WillHaveMagicProperties;

/**
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
interface EffectInterface extends WillHaveMagicProperties
{
    public function __toString();

    public function source(): ?PlayerInterface;

    public function setSource(PlayerInterface $player): void;
}
