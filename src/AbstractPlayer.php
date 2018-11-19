<?php

namespace Aesonus\TurnGame;

use Aesonus\TurnGame\Contracts\{
    PlayerInterface,
    ActionInterface,
    ActionFactoryInterface
};

/**
 * Base class for players in a turn based game
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
abstract class AbstractPlayer implements PlayerInterface
{

    const NO_NAME = '__NA__';

    /**
     *
     * @var float 
     */
    protected $initiative;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var ActionFactoryInterface
     */
    protected $actionFactory;

    public function __construct(ActionFactoryInterface $actionFactory)
    {
        $this->actionFactory = $actionFactory;
    }

    public function initiative(): ?float
    {
        return $this->initiative;
    }

    public function setInitiative(float $initiative): void
    {
        $this->initiative = $initiative;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function __toString()
    {
        return $this->name() ?? static::NO_NAME;
    }
}
