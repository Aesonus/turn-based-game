<?php

namespace Aesonus\Tests;

use Aesonus\TurnGame\Turn;
use Aesonus\TurnGame\AbstractAction;
use Aesonus\TurnGame\Contracts\PlayerInterface;
use Aesonus\TurnGame\Exceptions\CounterActionException;

/**
 * Test the base Turn class
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class TurnTest extends \Aesonus\TestLib\BaseTestCase
{

    public $testObj;
    public $actionStack;

    protected function setUp()
    {
        $this->actionStack = new \SplStack();
        $this->testObj = new Turn($this->actionStack);
        parent::setUp();
    }

    /**
     * @test
     */
    public function setPlayerSetsThePlayerProperty()
    {
        $expected = $this->newMockPlayer();
        $this->testObj->setPlayer($expected);
        $actual = $this->getPropertyValue($this->testObj, 'player');
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     */
    public function playerGetsThePlayerProperty()
    {
        $expected = $this->newMockPlayer();
        $this->setPropertyValue($this->testObj, 'player', $expected);
        $actual = $this->testObj->player();
        $this->assertSame($expected, $actual);
    }
    
    /**
     * @test
     */
    public function playerReturnsNullIfNotSet()
    {
        $actual = $this->testObj->player();
        $this->assertNull($actual);
    }
    
    /**
     * @test
     */
    public function pushActionPushesActionOntoActionStack()
    {
        $expected = $this->newMockAction();
        $this->testObj->pushAction($expected);
        $actual = $this->actionStack->pop();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     */
    public function currentActionReturnsTheNextActionOnTheActionStack()
    {
        $expected = $this->newMockAction();
        
        $this->actionStack->push($this->newMockAction());
        $this->actionStack->push($expected);
                
        $actual = $this->testObj->currentAction();
        $this->assertSame($expected, $actual);
    }
    
    /**
     * @test
     */
    public function currentActionDoesNotPopActionOffTheActionStack()
    {
        $expected_count = 2;
        $this->actionStack->push($this->newMockAction());
        $this->actionStack->push($this->newMockAction());
        
        //SUT
        $this->testObj->currentAction();
        $this->assertCount($expected_count, $this->actionStack);
    }
    
    /**
     * @test
     */
    public function currentActionDoesNotCallResolveOnTheNextActionInActionStack()
    {
        $action = $this->newMockAction();
        $this->actionStack->push($action);
        
        $action->expects($this->never())->method('resolve');
        //SUT
        $this->testObj->currentAction();
    }
    
    /**
     * @test
     */
    public function currentActionReturnsNullIfNoActionsAreOnTheActionStack()
    {
        $this->assertNull($this->testObj->currentAction());
    }
    
    /**
     * @test
     */
    public function popActionReturnsTheNextActionOnTheActionStack()
    {
        $expected = $this->newMockAction();
        
        $this->actionStack->push($this->newMockAction());
        $this->actionStack->push($expected);
                
        $actual = $this->testObj->popAction();
        $this->assertSame($expected, $actual);
    }
    
    /**
     * @test
     */
    public function popActionPopsTheActionOffTheActionStack()
    {
        $expected_count = 1;
        $this->actionStack->push($this->newMockAction());
        $this->actionStack->push($this->newMockAction());
        
        //SUT
        $this->testObj->popAction();
        $this->assertCount($expected_count, $this->actionStack);
    }
    
    /**
     * @test
     */
    public function popActionsReturnsNullIfNotActionsAreOnTheActionStack()
    {
        $this->assertNull($this->testObj->popAction());
    }
    
    /**
     * @test
     */
    public function popActionDoesNotCallResolveOnThePoppedAction()
    {
        $action = $this->newMockAction();
        $this->actionStack->push($action);
        
        $action->expects($this->never())->method('resolve');
        //SUT
        $this->testObj->popAction();
    }
    
    /**
     * @test
     */
    public function resolveNextActionCallsResolveOnTheNextActionOnTheActionStack()
    {
        $action = $this->setupActionStack();
        $action->expects($this->once())->method('resolve');
        $this->testObj->resolveNextAction();
    }
    
    /**
     * @test
     */
    public function resolveNextActionPopsActionOffOfTheActionStackIfNoExceptionIsThrown()
    {
        $this->setupActionStack();
        $expected_count = 1;
        $this->testObj->resolveNextAction();
        $this->assertCount($expected_count, $this->actionStack);
    }
    
    /**
     * @test
     */
    public function resolveNextActionDoesNotPopActionOffOfTheActionStackIfExceptionThrown()
    {
        $action = $this->setupActionStack();
        
        // Use this exception because it isn't abstract
        $action->expects($this->once())->method('resolve')
            ->willThrowException(new CounterActionException());
        $this->expectException(CounterActionException::class);
        
        $expected_count = 2;
        $this->testObj->resolveNextAction();
        $this->assertCount($expected_count, $this->actionStack);
    }
    
    /**
     * @test
     */
    public function resolveNextActionKeepsTheSameActionOnTheActionStackIfExceptionThrown()
    {
        $action = $this->setupActionStack();
        
        // Use this exception because it isn't abstract
        $action->expects($this->once())->method('resolve')
            ->willThrowException(new CounterActionException());
        $this->expectException(CounterActionException::class);
        
        $this->testObj->resolveNextAction();
        $this->assertSame($action, $this->actionStack[0]);
    }
    
    /**
     * @test
     */
    public function resolveNextActionReturnsNullIfNoActionsAreOnTheActionStack()
    {
        $this->assertNull($this->testObj->resolveNextAction());
    }
    
    protected function setupActionStack()
    {
        $first_action = $this->newMockAction();
        $this->actionStack->push($this->newMockAction());
        $this->actionStack->push($first_action);
        return $first_action;
    }

    protected function newMockAction()
    {
        return $this->getMockForAbstractClass(AbstractAction::class);
    }

    protected function newMockPlayer(): \PHPUnit_Framework_MockObject_MockObject
    {
        return $this->getMockForAbstractClass(PlayerInterface::class);
    }
}
