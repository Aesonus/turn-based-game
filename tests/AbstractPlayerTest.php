<?php

namespace Aesonus\Tests;

use Aesonus\TurnGame\AbstractPlayer;
use Aesonus\TurnGame\Contracts\ActionFactoryInterface;

/**
 * Tests the base Player class
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class AbstractPlayerTest extends \Aesonus\TestLib\BaseTestCase
{
    public $testObj;
    public $mockActionFactory;
    
    protected function setUp()
    {
        $this->mockActionFactory = $this
            ->getMockForAbstractClass(ActionFactoryInterface::class);
        $this->testObj = $this->getMockBuilder(AbstractPlayer::class)
            ->setConstructorArgs([$this->mockActionFactory])
            ->getMockForAbstractClass();
        parent::setUp();
    }
    
    /**
     * @test
     */
    public function setInitiativeSetsTheInitiativeProperty()
    {
        $expected = 23.4;
        $this->testObj->setInitiative($expected);
        $actual = $this->getPropertyValue($this->testObj, 'initiative');
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     */
    public function setNameSetsTheNameProperty()
    {
        $expected = 'Name';
        $this->testObj->setName($expected);
        $actual = $this->getPropertyValue($this->testObj, 'name');
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     */
    public function initiativeGetsTheInitiativeProperty()
    {
        $expected = 23.4;
        $this->setPropertyValue($this->testObj, 'initiative', $expected);
        $actual = $this->testObj->initiative();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     */
    public function initiativeReturnNullIfNotSet()
    {
        $this->assertNull($this->testObj->initiative());
    }
    
    /**
     * @test
     */
    public function nameGetsTheNameProperty()
    {
        $expected = "testname";
        $this->setPropertyValue($this->testObj, 'name', $expected);
        $actual = $this->testObj->name();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     */
    public function nameReturnNullIfNotSet()
    {
        $this->assertNull($this->testObj->name());
    }
    
    /**
     * @test
     */
    public function __toStringReturnsNameProperty()
    {
        $expected = "testname";
        $this->setPropertyValue($this->testObj, 'name', $expected);
        $actual = (string)$this->testObj;
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     */
    public function __toStringReturns__NA__IfNameNotSet()
    {
        $expected = '__NA__';
        $actual = (string)$this->testObj;
        $this->assertEquals($expected, $actual);
    }
}
