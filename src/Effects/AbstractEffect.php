<?php

namespace Aesonus\TurnGame\Effects;

use Aesonus\PhpMagic\HasInheritedMagicProperties;
use Aesonus\PhpMagic\ImplementsMagicMethods;
use Aesonus\TurnGame\Contracts\EffectInterface;
use Aesonus\TurnGame\Contracts\PlayerInterface;

/**
 * Defines common functionality
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 *
 * @property int $amount The amount of effect expressed as an integer, use for things
 * like damage or healing
 */
abstract class AbstractEffect implements EffectInterface
{
    use HasInheritedMagicProperties;
    use ImplementsMagicMethods;

    /**
     *
     * @var PlayerInterface
     */
    protected $source;

    public function setSource(PlayerInterface $player): void
    {
        $this->source = $player;
    }

    public function source(): ?PlayerInterface
    {
        return $this->source;
    }

    public static function newEffect(?array $constructor_args = null): AbstractEffect
    {
        return new self;
    }
}
