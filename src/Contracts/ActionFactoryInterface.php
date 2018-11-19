<?php

namespace Aesonus\TurnGame\Contracts;

/**
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
interface ActionFactoryInterface
{
    /**
     * MUST return a new action parsed from string representation
     * @param string $action
     * @return ActionInterface
     */
    public function fromString(string $action): ActionInterface;
    
    /**
     * MUST return a new action
     * @return ActionInterface
     */
    public function newAction(): ActionInterface;
}
