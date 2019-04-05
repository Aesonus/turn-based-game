<?php

namespace Aesonus\TurnGame\Contracts;

/**
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
interface ActionFactoryInterface
{
    /**
     * MUST return a new action created from string representation
     * @param string $action
     * @return ActionInterface
     */
    public function fromString(string $action): ActionInterface;
    
    /**
     * MUST return a new action of type given or a default type
     * @param string|null [optional] $of_type
     * @return ActionInterface
     */
    public function newAction(?string $of_type = null): ActionInterface;
}
