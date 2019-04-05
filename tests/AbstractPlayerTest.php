<?php

namespace Aesonus\Tests;

use Aesonus\TestLib\BaseTestCase;
use Aesonus\TurnGame\AbstractPlayer;
use Aesonus\TurnGame\Contracts\ActionFactoryInterface;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Tests the base Player class
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class AbstractPlayerTest extends BaseTestCase
{
    /**
     *
     * @var AbstractPlayer|MockObject
     */
    public $testObj;

    /**
     *
     * @var ActionFactoryInterface|MockObject
     */
    public $mockActionFactory;

    protected function setUp(): void
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
    public function initiativeGetsTheSetInitiativeValue()
    {
        $expected = 23.4;
        $this->testObj->setInitiative($expected);
        $actual = $this->testObj->initiative();
        $this->assertEquals($expected, $actual);
        $actual = $this->testObj->initiative;
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function initiativeReturnNullIfNotSet()
    {
        $this->assertNull($this->testObj->initiative());
        $this->assertNull($this->testObj->initiative);
        $this->assertFalse(isset($this->testObj->initiative));
    }

    /**
     * @test
     */
    public function nameGetsTheSetName()
    {
        $expected = "testname";
        $this->testObj->setName($expected);
        $actual = $this->testObj->name();
        $this->assertEquals($expected, $actual);
        $actual = $this->testObj->name;
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
    public function __toStringReturnsTheSetNameValue()
    {
        $expected = "testname";
        $this->testObj->setName($expected);
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
