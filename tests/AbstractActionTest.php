<?php

namespace Aesonus\Tests;

use Aesonus\TestLib\BaseTestCase;
use Aesonus\TurnGame\AbstractAction;
use Aesonus\TurnGame\Contracts\EffectInterface;
use Aesonus\TurnGame\Contracts\PlayerInterface;
use InvalidArgumentException;
use stdClass;

/**
 * Tests the Base Action class
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class AbstractActionTest extends BaseTestCase
{

    /**
     *
     * @var AbstractAction
     */
    protected $testObj;

    protected function setUp(): void
    {
        $this->testObj = $this->newMockAction();
        parent::setUp();
    }

    /**
     * @test
     */
    public function setPlayerReturnsAction()
    {
        $expected = $this->testObj;
        $actual = $this->testObj
            ->setPlayer($this->newMockPlayer());
        $this->assertSame($expected, $actual);
        return $actual;
    }

    /**
     * @test
     * @depends setPlayerReturnsAction
     */
    public function playerReturnsThePreviouslySetPlayer($testObj)
    {
        $expected = $this->newMockPlayer();
        $actual = $testObj->player();
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
    public function setTypeReturnsAction()
    {
        $expected = $this->testObj;
        $actual = $this->testObj->setType('test');
        $this->assertSame($expected, $actual);
        return $actual;
    }

    /**
     * @test
     * @depends setTypeReturnsAction
     */
    public function getTypeReturnsType($testObj)
    {
        $expected = 'test';
        $actual = $testObj->getType();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     * @dataProvider isResolvedReturnsIfActionIsResolvedDataProvider
     */
    public function isResolvedReturnsIfActionIsResolved($expected)
    {
        $this->testObj = $this->testObj->setIsResolved($expected);
        $actual = $this->testObj->isResolved();
        $this->assertEquals($expected, $actual);
    }

    /**
     * Data Provider
     */
    public function isResolvedReturnsIfActionIsResolvedDataProvider()
    {
        return [
            [true],
            [false]
        ];
    }

    /**
     * @test
     */
    public function isResolvedIsFalseByDefault()
    {
        $this->assertFalse($this->testObj->isResolved());
    }

    /**
     * @test
     */
    public function setIsResolvedReturnsAction()
    {
        $expected = $this->testObj;
        $actual = $this->testObj->setIsResolved(true);
        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function modifiesReturnsAction()
    {
        $expected = $this->testObj;
        $actual = $this->testObj->modifies($this->newMockAction());
        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function getModifiedActionGetModifiedActionOnReturnedAction()
    {
        $expected = $this->newMockAction();
        $testObj = $this->testObj->modifies($expected);
        $actual = $testObj->getModifiedAction();
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
    public function targetsReturnsAAction()
    {
        $expected = $this->testObj;
        $actual = $this->testObj->targets($this->newMockPlayer());
        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     * @dataProvider invalidTargetsDataProvider
     */
    public function targetsThrowsInvalidArgumentExceptionIfTargetsArentPlayers($targets)
    {
        $this->expectException(InvalidArgumentException::class);
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
            [new stdClass()],
            [[new stdClass()]]
        ];
    }

    /**
     * @test
     */
    public function getTargetsGetsTheTargetsOnAction()
    {
        $expected = [$this->newMockPlayer()];
        $this->testObj->targets($expected);
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

    protected function newMockPlayer()
    {
        return $this->getMockForAbstractClass(PlayerInterface::class);
    }

    protected function newMockEffect()
    {
        return $this->getMockForAbstractClass(EffectInterface::class);
    }

    /**
     * @test
     */
    public function arrayAccessGetsAppendsIssetsAndUnsets()
    {
        $expected = $this->newMockEffect();

        $this->testObj[] = $expected;
        $this->assertEquals($expected, $this->testObj[0]);

        unset($this->testObj[0]);
        $this->assertFalse(isset($this->testObj[0]));
    }

    /**
     * @test
     */
    public function iteratorAccessIteratesEffectsInOrder()
    {
        $expected = [
            $this->newMockEffect(),
            $this->newMockEffect(),
            $this->newMockEffect(),
        ];
        array_map([$this->testObj, 'offsetSet'],  array_keys($expected), $expected);
        foreach ($this->testObj as $index => $actual) {
            $this->assertEquals($expected[$index], $actual);
        }
    }
}
