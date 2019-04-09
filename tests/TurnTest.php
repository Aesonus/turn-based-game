<?php

namespace Aesonus\Tests;

use Aesonus\TestLib\BaseTestCase;
use Aesonus\TurnGame\AbstractAction;
use Aesonus\TurnGame\Contracts\PlayerInterface;
use Aesonus\TurnGame\Exceptions\CounterActionException;
use Aesonus\TurnGame\Storage\RuntimeTurnStorage;
use Aesonus\TurnGame\Turn;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionProperty;
use SplStack;

/**
 * Test the base Turn class
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class TurnTest extends BaseTestCase
{
    public $testObj;
    public $actionStack;
    public $turnStorage;

    protected function setUp(): void
    {
        $this->turnStorage = new RuntimeTurnStorage();
        $this->actionStack = $this->turnStorage->__getActionStack();

        $this->testObj = new Turn($this->turnStorage);
        parent::setUp();
    }

    /**
     * @test
     */
    public function allActionsReturnsSplStackInstance()
    {
        $actual = $this->testObj->allActions();
        $this->assertSame($this->actionStack, $actual);
    }

    /**
     * @test
     */
    public function playerGetsTheSetPlayer()
    {
        $expected = $this->newMockPlayer();
        $this->testObj->setPlayer($expected);
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
    public function resolveNextActionReturnsResolvedAction()
    {
        $action = $this->setupActionStack();
        //When implementing this method, it must return a cloned version
        //of itself with isResolved() returning true
        $action->expects($this->once())->method('resolve')
            ->willReturnCallback(function () use ($action) {
                $ref = new ReflectionProperty($action, 'resolved');
                $ref->setAccessible(true);
                $ref->setValue($action, true);
                return clone $action;
            });
        $this->assertTrue($this->testObj->resolveNextAction()->isResolved());
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

    protected function newMockPlayer(): MockObject
    {
        return $this->getMockForAbstractClass(PlayerInterface::class);
    }
}
