<?php

/*
 * This file is part of the TurnGame project.
 *
 * (c) Cory Laughlin <corylcomposinger@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aesonus\TurnGame\Exceptions;

/**
 * 
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class InvalidActionException extends \RuntimeException
{
    const TARGETS_ALREADY_SET = 1;
    const MODIFIED_ACTION_ALREADY_SET = 2;
}
