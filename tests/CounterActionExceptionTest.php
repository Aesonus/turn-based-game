<?php

namespace Aesonus\Tests;

use Aesonus\TurnGame\Exceptions\CounterActionException;
use Aesonus\TurnGame\Contracts\ActionInterface;

/**
 * Tests the CounterActionException class
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class CounterActionExceptionTest extends \Aesonus\TestLib\BaseTestCase
{
    public $testObj;
    public $expectedAction;

    protected function setUp(): void
    {
        $this->expectedAction = $this->getMockForAbstractClass(ActionInterface::class);
        try {
            throw new CounterActionException();
        } catch (CounterActionException $exc) {
            $this->testObj = $exc;
        }

        parent::setUp();
    }

    /**
     * @test
     */
    public function getActionGetsTheSetAction()
    {
        $this->testObj->setAction($this->expectedAction);
        $actual = $this->testObj->getAction();
        $this->assertSame($this->expectedAction, $actual);
    }

    /**
     * @test
     */
    public function getActionReturnsNullIfActionNotSet()
    {
        $this->assertNull($this->testObj->getAction());
    }
}
