<?php

namespace Aesonus\TurnGame\Contracts;

use Aesonus\PhpMagic\WillHaveMagicProperties;

/**
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
interface EffectInterface extends WillHaveMagicProperties, \JsonSerializable
{
    /**
     *
     * @return string A string description of the effect
     */
    public function __toString();

    /**
     *
     * @return PlayerInterface|null Return the source of
     * the effect
     */
    public function source(): ?PlayerInterface;

    /**
     * Sets the source of the effect
     * @param PlayerInterface $player
     * @return void
     */
    public function setSource(PlayerInterface $player): void;

    /**
     * MUST return a new EffectInterface that is combined with this object
     * @param EffectInterface $effect
     * @return EffectInterface
     */
    public function combineEffect(EffectInterface $effect): EffectInterface;

    /**
     * MUST return a new instance of the current object's class with given
     * constructor arguments
     * @param array $constructor_args [optional]
     * @return \Aesonus\TurnGame\Contracts\AbstractEffect
     */
    public static function newEffect(array $constructor_args = []): EffectInterface;

    /**
     * MUST return a new instance of the effect described by $json
     * @param string $json
     * @return \Aesonus\TurnGame\Contracts\EffectInterface
     */
    public static function newEffectFromJson(string $json): EffectInterface;
}
