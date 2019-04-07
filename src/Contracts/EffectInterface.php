<?php

namespace Aesonus\TurnGame\Contracts;

use Aesonus\PhpMagic\WillHaveMagicProperties;

/**
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
interface EffectInterface extends WillHaveMagicProperties
{
    /**
     *
     * @return string A string description of the effect
     */
    public function __toString();

    /**
     *
     * @return \Aesonus\TurnGame\Contracts\PlayerInterface|null Return the source of
     * the effect
     */
    public function source(): ?PlayerInterface;

    /**
     * Sets the source of the effect
     * @param \Aesonus\TurnGame\Contracts\PlayerInterface $player
     * @return void
     */
    public function setSource(PlayerInterface $player): void;
}
