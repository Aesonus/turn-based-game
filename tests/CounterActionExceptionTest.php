<?php

namespace Aesonus\Tests;

use Aesonus\TurnGame\{
    Exceptions\CounterActionException,
    Contracts\ActionInterface
};

/**
 * Tests the CounterActionException class
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class CounterActionExceptionTest extends \Aesonus\TestLib\BaseTestCase
{
    
    public $testObj;
    public $expectedAction;
    
    protected function setUp()
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
    public function setActionSetsTheActionProperty()
    {
        $this->testObj->setAction($this->expectedAction);
        $actual = $this->getPropertyValue($this->testObj, 'action');
        $this->assertSame($this->expectedAction, $actual);
    }
    
    /**
     * @test
     */
    public function getActionGetsTheActionProperty()
    {
        $this->setPropertyValue($this->testObj, 'action', $this->expectedAction);
        $actual = $this->testObj->getAction();
        $this->assertSame($this->expectedAction, $actual);
    }
    
    /**
     * @test
     */
    public function getActionReturnsNullIfActionPropertyNotSet()
    {
        $this->assertNull($this->testObj->getAction());
    }
}
