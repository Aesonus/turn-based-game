<?php

namespace Aesonus\TurnGame;

use Aesonus\PhpMagic\HasInheritedMagicProperties;
use Aesonus\TurnGame\Contracts\ActionFactoryInterface;
use Aesonus\TurnGame\Contracts\ActionInterface;
use Aesonus\TurnGame\Contracts\PlayerInterface;
use Aesonus\TurnGame\Storage\PlayerStorageInterface;

/**
 * Base class for players in a turn based game
 *
 * @property-read ?float $initiative The speed of this character
 * @property-read ?string $name Name of the character
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
abstract class AbstractPlayer implements PlayerInterface
{
    use HasInheritedMagicProperties;
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

    /**
     *
     * @var PlayerStorageInterface
     */
    protected $storage;

    public function __construct(
        ActionFactoryInterface $actionFactory,
        PlayerStorageInterface $storage,
        $id = null
    ) {
        $this->actionFactory = $actionFactory;
        $this->storage = $storage->newInstance()->loadRecord($id);
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

    public function __toString(): string
    {
        return $this->name() ?? static::NO_NAME;
    }

    public function newAction(): ActionInterface
    {
        return $this->actionFactory->newAction()->setPlayer($this);
    }

    public function __get($name)
    {
        return $this->magicGet($name);
    }

    public function __isset($name)
    {
        return $this->magicIsset($name);
    }

    protected function __getInitiative()
    {
        return $this->initiative();
    }

    protected function __issetInitiative()
    {
        return $this->initiative() !== null;
    }
}
