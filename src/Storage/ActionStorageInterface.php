<?php

namespace Aesonus\TurnGame\Storage;

/**
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
interface ActionStorageInterface
{
    public function setModifies(array $modified_actions): bool;

    public function getModifies(): ?array;

    public function setTargets(array $targets): bool;

    public function getTargets(): ?array;

    public function setPlayer(PlayerInterface $player): bool;

    public function getPlayer() : ?PlayerInterface;

    public function setEffects(array $effects): bool;

    public function getEffects(): array;

    public function setType($type): bool;

    public function getType();

    public function setResolved(bool $resolved): bool;

    public function getResolved(): ?bool;

}
