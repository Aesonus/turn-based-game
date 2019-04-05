<?php

namespace Aesonus\Tests;

use Aesonus\TurnGame\Exceptions\GameException;
use Aesonus\TurnGame\Contracts\PlayerInterface;

/**
 * Tests the game exception
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class GameExceptionTest extends \Aesonus\TestLib\BaseTestCase
{
    public $testObj;
    public $expectedPlayer;

    protected function setUp(): void
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
    public function getPlayerGetsTheSetPlayer()
    {
        $this->testObj->setPlayer($this->expectedPlayer);
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
