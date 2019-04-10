<?php

namespace Aesonus\Tests;

use Aesonus\TestLib\BaseTestCase;
use Aesonus\TurnGame\AbstractPlayer;
use Aesonus\TurnGame\Contracts\EffectInterface;
use Aesonus\TurnGame\Contracts\PlayerInterface;
use Aesonus\TurnGame\Effects\AbstractEffect;
use PHPUnit\Framework\MockObject\MockObject;

/**
 *
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class AbstractEffectTest extends BaseTestCase
{
    /**
     *
     * @var MockObject|EffectInterface
     */
    public $testObj;

    /**
     *
     * @var MockObject|PlayerInterface
     */
    protected $player;

    protected function setUp(): void
    {
        parent::setUp();
        $this->testObj = $this->getMockBuilder(AbstractEffect::class)
            ->setMockClassName('EffectObject')
            ->getMockForAbstractClass();
        $this->player = $this->getMockBuilder(AbstractPlayer::class)
            ->disableOriginalConstructor()->getMockForAbstractClass();
    $this->player->setName('test player');
    }

    /**
     * @test
     */
    public function sourceReturnsTheSetSourceOfTheEffect()
    {
        $expected = $this->player;
        $this->testObj->setSource($expected);
        $actual = $this->testObj->source();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function newEffectReturnsANewEffectOfTheObjectType()
    {
        $actual = $this->testObj->newEffect();
        $this->assertInstanceOf('EffectObject', $actual);
    }

    /**
     * @test
     */
    public function newEffectFromJsonReturnsObjectEqualToSerializedObject()
    {
        $this->testObj->setSource($this->player);
        $actual = $this->testObj->newEffectFromJson($this->testObj->jsonSerialize());
        $this->assertEquals($this->testObj, $actual);
    }
}
