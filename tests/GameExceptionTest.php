<?php

namespace Aesonus\Tests;

use Aesonus\TurnGame\{
    Exceptions\GameException,
    Contracts\PlayerInterface
};

/**
 * Tests the game exception
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class GameExceptionTest extends \Aesonus\TestLib\BaseTestCase
{

    public $testObj;
    public $expectedPlayer;

    protected function setUp()
    {
        try {
            throw new class extends GameException {
                
            };
        } catch (GameException $exc) {
            $this->testObj = $exc;
        }
        $this->expectedPlayer = $this->getMockForAbstractClass(PlayerInterface::class);
        parent::setUp();
    }

    /**
     * @test
     */
    public function setPlayerSetsThePlayerProperty()
    {
        $this->testObj->setPlayer($this->expectedPlayer);
        $actual = $this->getPropertyValue($this->testObj, 'player');
        $this->assertSame($this->expectedPlayer, $actual);
    }

    /**
     * @test
     */
    public function getPlayerGetsThePlayerProperty()
    {
        $this->setPropertyValue($this->testObj, 'player', $this->expectedPlayer);
        $actual = $this->testObj->getPlayer();
        $this->assertSame($this->expectedPlayer, $actual);
    }

    /**
     * @test
     */
    public function getPlayerReturnsNullIfPlayerPropertyNotSet()
    {
        $this->assertNull($this->testObj->getPlayer());
    }
}
