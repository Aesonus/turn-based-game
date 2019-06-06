<?php

namespace Aesonus\TurnGame\Storage;

use Aesonus\PhpMagic\WillHaveMagicProperties;

/**
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
interface PlayerStorageInterface extends BaseStorageInterface
{
    public function getName() : ?string;

    public function setName(string $name): bool;

    public function getInitiative() : ?float;

    public function setInitiative(float $initiative): bool;
}
