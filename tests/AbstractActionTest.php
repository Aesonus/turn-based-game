<?php

namespace Aesonus\Tests;

use Aesonus\TurnGame\AbstractAction;
use Aesonus\TurnGame\Contracts\PlayerInterface;
use Aesonus\TurnGame\Exceptions\InvalidActionException;

/**
 * Tests the Base Action class
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class AbstractActionTest extends \Aesonus\TestLib\BaseTestCase
{

    protected $testObj;

    protected function setUp()
    {

        $this->testObj = $this->newMockAction();
        parent::setUp();
    }

    /**
     * @test
     */
    public function setPlayerReturnsNewAction()
    {
        $expected = $this->testObj;
        $actual = $this->testObj
            ->setPlayer($this->newMockPlayer());
        $this->assertNotSame($expected, $actual);
    }

    /**
     * @test
     */
    public function setPlayerSetsThePlayerProperty()
    {
        $expected = $this->newMockPlayer();
        $newAction = $this->testObj->setPlayer($expected);
        $actual = $this
            ->getPropertyValue($newAction, 'player');
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     */
    public function playerReturnsThePlayerProperty()
    {
        $expected = $this->newMockPlayer();
        $this->setPropertyValue($this->testObj, 'player', $expected);
        $actual = $this->testObj->player();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     */
    public function playerReturnsNullIfPlayerPropertyNotSet()
    {
        $this->assertNull($this->testObj->player());
    }
    
    /**
     * @test
     */
    public function setTypeReturnsNewAction()
    {
        $expected = $this->testObj;
        $actual = $this->testObj->setType('test');
        $this->assertNotSame($expected, $actual);
    }
    
    /**
     * @test
     * @dataProvider setTypeSetsTheTypePropertyDataProvider
     */
    public function setTypeSetsTheTypeProperty($expected)
    {
        $newAction = $this->testObj->setType($expected);
        $actual = $this
            ->getPropertyValue($newAction, 'type');
        $this->assertEquals($expected, $actual);
    }

    /**
     * Data Provider
     */
    public function setTypeSetsTheTypePropertyDataProvider()
    {
        return [
            ['test'],
            [new \stdClass()],
            [23]
        ];
    }
    
    /**
     * @test
     */
    public function getTypeReturnsTypeProperty()
    {
        $expected = 'test';
        $this->setPropertyValue($this->testObj, 'type', $expected);
        $actual = $this->testObj->getType();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @dataProvider isResolvedReturnsResolvedPropertyDataProvider
     */
    public function isResolvedReturnsResolvedProperty($expected)
    {
        $this->setPropertyValue($this->testObj, 'resolved', $expected);
        $actual = $this->testObj->isResolved();
        $this->assertEquals($expected, $actual);
    }

    /**
     * Data Provider
     */
    public function isResolvedReturnsResolvedPropertyDataProvider()
    {
        return [
            [true],
            [false]
        ];
    }
    
    /**
     * @test
     */
    public function isResolvedPropertyIsFalseByDefault()
    {
        $this->assertFalse($this->testObj->isResolved());
    }
    
    /**
     * @test
     */
    public function modifiesSetsTheModifiedActionProperty()
    {
        $expected = $this->newMockAction();
        $newAction = $this->testObj->modifies($expected);
        $actual = $this->getPropertyValue($newAction, 'modified_action');
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     */
    public function modifiesReturnsNewAction()
    {
        $expected = $this->testObj;
        $actual = $this->testObj->modifies($this->newMockAction());
        $this->assertNotSame($expected, $actual);
    }
    
    /**
     * @test
     */
    public function modifiesThrowsInvalidActionExceptionIfTargetsAlreadySet()
    {
        $this->expectException(InvalidActionException::class);
        $newAction = $this->testObj->targets($this->newMockPlayer());
        $newAction->modifies($this->newMockAction());
    }
    
    /**
     * @test
     */
    public function getModifiedActionGetModifiedActionProperty()
    {
        $expected = $this->newMockAction();
        $this->setPropertyValue($this->testObj, 'modified_action', $expected);
        $actual = $this->testObj->getModifiedAction();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     */
    public function getModifiedActionReturnsNullIfModifiedActionPropertyNotSet()
    {
        $this->assertNull($this->testObj->getModifiedAction());
    }
    
    /**
     * @test
     */
    public function targetsReturnsANewAction()
    {
        $expected = $this->testObj;
        $actual = $this->testObj->targets($this->newMockPlayer());
        $this->assertNotSame($expected, $actual);
    }
    
    /**
     * @test
     * @dataProvider targetsSetsTheTargetPropertyToArrayOfPlayersDataProvider
     */
    public function targetsSetsTheTargetPropertyToArrayOfPlayers($expected)
    {
        $newAction = $this->testObj->targets($expected);
        //Make the expected value always an array
        $expected = is_array($expected)? $expected : [$expected];
        $actual = $this->getPropertyValue($newAction, 'targets');
        $this->assertEquals($expected, $actual);
    }

    /**
     * Data Provider
     */
    public function targetsSetsTheTargetPropertyToArrayOfPlayersDataProvider()
    {
        return [
            'string' => [$this->newMockPlayer()],
            'array' => [[$this->newMockPlayer(), $this->newMockPlayer()]]
        ];
    }
    
    /**
     * @test
     * @dataProvider invalidTargetsDataProvider
     */
    public function targetsThrowsInvalidArgumentExceptionIfTargetsArentPlayers($targets)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->testObj->targets($targets);
    }

    /**
     * Data Provider
     */
    public function invalidTargetsDataProvider()
    {
        return [
            ['not valid'],
            [['not', 'valid']],
            [new \stdClass()],
            [[new \stdClass()]]
        ];
    }
    
    /**
     * @test
     */
    public function targetsThrownInvalidActionExceptionIfGetModifiedActionAlreadySet()
    {
        $this->expectException(InvalidActionException::class);
        $newAction = $this->testObj->modifies($this->newMockAction());
        $newAction->targets($this->newMockPlayer());
    }
    
    /**
     * @test
     */
    public function getTargetsGetsTheTargetsProperty()
    {
        $expected = [$this->newMockPlayer()];
        $this->setPropertyValue($this->testObj, 'targets', $expected);
        $actual = $this->testObj->getTargets();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     */
    public function getTargetsReturnsNullIfTargetsPropertyNotSet()
    {
        $this->assertNull($this->testObj->getModifiedAction());
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
