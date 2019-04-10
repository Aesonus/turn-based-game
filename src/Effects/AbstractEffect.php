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

    /**
     *
     * @var int
     */
    protected $amount;

    public function setSource(PlayerInterface $player): void
    {
        $this->source = $player;
    }

    public function source(): ?PlayerInterface
    {
        return $this->source;
    }

    public static function newEffect(array $constructor_args = []): EffectInterface
    {
        return new static(...$constructor_args);
    }

    public function jsonSerialize(): string
    {
        return json_encode($this->getAllAttributes());
    }

    /**
     * Override this if you need to add more attributes to the array
     * @return array
     */
    protected function getAllAttributes(): array
    {
        $attributes = [
            'class' => get_class($this),
            'source' => $this->source()->name(),
            'amount' => $this->amount,
        ];
        return $attributes;
    }

    public static function newEffectFromJson(string $json): EffectInterface
    {
        $attributes = json_decode($json);
        $new = new $attributes->class;
        $new->source = $attributes->source;
        $new->amount = $attributes->amount;
        return $new;
    }
}
