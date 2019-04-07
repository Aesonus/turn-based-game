<?php

namespace Aesonus\Tests;

use Aesonus\TestLib\BaseTestCase;
use Aesonus\TurnGame\AbstractAction;
use Aesonus\TurnGame\AbstractPlayer;
use Aesonus\TurnGame\Contracts\ActionFactoryInterface;
use Aesonus\TurnGame\Contracts\PlayerInterface;
use Aesonus\TurnGame\Contracts\TurnFactoryInterface;
use Aesonus\TurnGame\Contracts\TurnInterface;
use Aesonus\TurnGame\Game;
use Aesonus\TurnGame\Turn;
use PHPUnit\Framework\MockObject\MockObject;
use SplStack;

/**
 * Tests the game class
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class GameTest extends BaseTestCase
{

    /**
     *
     * @var Game
     */
    public $testObj;
    public $playerQueue;
    public $mockTurnFactory;
    public $mockActionFactory;

    protected function setUp(): void
    {
        $this->mockTurnFactory = $this
            ->getMockForAbstractClass(TurnFactoryInterface::class);
        $this->mockActionFactory = $this
            ->getMockForAbstractClass(ActionFactoryInterface::class);
        $this->testObj = new Game($this->mockTurnFactory, $this->mockActionFactory);
        parent::setUp();
    }

    /**
     * @test
     * @dataProvider setPlayersDataProvider
     */
    public function setPlayersEnqueuesPlayerToPlayersQueueWithPriority(
        $expected_players,
        $expected_priority_order
    )
    {
        $this->testObj->setPlayers($expected_players);
        $playerQueue = $this->testObj->findPlayers();
        $this->assertCount(count($expected_players), $playerQueue);

        foreach ($playerQueue as $i => $actual) {
            $this->assertEquals($expected_priority_order[$i], $actual->initiative());
        }
    }

    /**
     * Data Provider
     */
    public function setPlayersDataProvider()
    {
        return [
            [
                [
                    $this->newMockPlayer(null, 1)
                ], [
                    1
                ]
            ],
            [
                [
                    $this->newMockPlayer(null, 1),
                    $this->newMockPlayer(null, 2),
                    $this->newMockPlayer(null, 3),
                ], [
                    3.0,
                    2.0,
                    1.0
                ]
            ],
            [
                [
                    $this->newMockPlayer(null, 1),
                    $this->newMockPlayer(null, 2),
                    $this->newMockPlayer(null, 2),
                ], [
                    2.0,
                    2.0,
                    1.0
                ]
            ],
        ];
    }

    /**
     * @test
     * @dataProvider findPlayersFindsSpecificPlayerInterfacesDataProvider
     */
    public function findPlayersFindsSpecificPlayerInterfaces($players, $find)
    {
        $this->testObj->setPlayers($players);
        $actual = $this->testObj->findPlayers($find);

        $this->assertContainsOnlyInstancesOf(PlayerInterface::class, $actual);

        //Get an array of all the names
        $actual_names = array_map(function ($value) {
            return $value->name();
        }, $actual);
        $this->assertArrayContainsValues($find, $actual_names);
    }

    /**
     * Data Provider
     */
    public function findPlayersFindsSpecificPlayerInterfacesDataProvider()
    {
        return [
            [[
                $this->newMockPlayer('Bob'),
                $this->newMockPlayer('Bill'),
                $this->newMockPlayer('Beau'),
                ], [
                    'Bob',
                    'Beau'
                ]],
        ];
    }

    /**
     * @test
     */
    public function findPlayersGetsAllPlayersInOrder()
    {
        $expected = [
            $this->newMockPlayer('Bob'),
            $this->newMockPlayer('Bill'),
            $this->newMockPlayer('Beau'),
        ];
        $this->testObj->setPlayers($expected);
        $actual = $this->testObj->findPlayers();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function findPlayersReturnsNullIfNoPlayersAreInGame()
    {
        $this->assertNull($this->testObj->findPlayers(['test']));
    }

    /**
     * @test
     * @dataProvider alreadyPresentTurnsDataProvider
     */
    public function setCurrentTurnPushesTheCurrentTurn($already_present_turns)
    {
        $expected = $this->newTurn();
        //Put some dummy turns in
        array_map([$this->testObj, 'setCurrentTurn'], $already_present_turns);

        $this->testObj->setCurrentTurn($expected);
        $actual = $this->testObj->currentTurn();
        $this->assertEquals($expected, $actual);
    }

    /**
     * Data Provider
     */
    public function alreadyPresentTurnsDataProvider()
    {
        return [
            [[]],
            [[$this->newTurn()]],
            [[$this->newTurn(), $this->newTurn()]]
        ];
    }

    /**
     * @test
     */
    public function currentTurnGetsTheLatestPushedTurn()
    {
        $expected = $this->newTurn();
        $this->testObj->setCurrentTurn($this->newTurn());
        $this->testObj->setCurrentTurn($expected);
        $actual = $this->testObj->currentTurn();

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function currentTurnReturnsNullIfTurnsPropertyNotSet()
    {
        $this->assertNull($this->testObj->currentTurn());
    }

    /**
     * @test
     * @dataProvider findTurnReturnsTurnAtIndexDataProvider
     */
    public function findTurnReturnsTurnAtIndex($turns, $index, $expected)
    {
        array_map([$this->testObj, 'setCurrentTurn'], $turns);
        $actual = $this->testObj->findTurn($index);
        $this->assertSame($expected, $actual);
    }

    /**
     * Data Provider
     */
    public function findTurnReturnsTurnAtIndexDataProvider()
    {
        $expected = $this->newTurn();
        return [
            [
                [
                    $this->newTurn(),
                    $this->newTurn(),
                    $expected,
                ],
                0,
                $expected
            ],
            [
                [
                    $expected,
                    $this->newTurn(),
                    $this->newTurn(),
                ],
                2,
                $expected
            ]
        ];
    }

    /**
     * @test
     */
    public function findTurnReturnsCurrentTurnAtIndex0()
    {
        $turns = [
            $this->newTurn(),
            $this->newTurn(),
            $this->newTurn(),
        ];
        array_map([$this->testObj, 'setCurrentTurn'], $turns);

        $expected = $this->testObj->currentTurn();
        $actual = $this->testObj->findTurn(0);
        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     * @dataProvider findTurnReturnsNullIfNoTurnAtIndexDataProvider
     */
    public function findTurnReturnsNullIfNoTurnAtIndex($turns, $index)
    {
        array_map([$this->testObj, 'setCurrentTurn'], $turns);

        $this->assertNull($this->testObj->findTurn($index));
    }

    /**
     * Data Provider
     */
    public function findTurnReturnsNullIfNoTurnAtIndexDataProvider()
    {
        return [
            [
                [
                    $this->newTurn(),
                    $this->newTurn(),
                    $this->newTurn(),
                ], 3
            ],
            [
                [
                    $this->newTurn(),
                    $this->newTurn(),
                ], 5
            ],
            [
                [], 0
            ]
        ];
    }

    /**
     * @test
     */
    public function allTurnsGetsAllTurns()
    {
        $expected = [
            $this->newTurn(),
            $this->newTurn(),
            $this->newTurn(),
        ];
        array_map([$this->testObj, 'setCurrentTurn'], $expected);

        $actual = $this->testObj->allTurns();

        $this->assertEquals(array_reverse($expected), $actual);
    }

    /**
     * @test
     */
    public function allTurnsReturnsNullIfNoTurnsTaken()
    {
        $this->assertNull($this->testObj->allTurns());
    }

    /**
     * @test
     */
    public function isStartedReturnsTrueIfTurnsPropertyIsNotEmpty()
    {
        $turns = [
            $this->newTurn(),
            $this->newTurn(),
            $this->newTurn(),
        ];
        array_map([$this->testObj, 'setCurrentTurn'], $turns);
        $this->assertTrue($this->testObj->isStarted());
    }

    /**
     * @test
     */
    public function isStartedReturnsFalseIfTurnsPropertyIsEmpty()
    {
        $this->assertFalse($this->testObj->isStarted());
    }

    /**
     * @test
     * @dataProvider nextPlayerDataProvider
     */
    public function nextPlayerReturnsTheNextPlayerInTheTurnOrder($players, $turns, $expected)
    {
        //Setup
        $this->testObj->setPlayers($players);
        array_map([$this->testObj, 'setCurrentTurn'], $turns);

        $actual = $this->testObj->nextPlayer();
        $this->assertEquals($expected, $actual);
    }

    /**
     * Data Provider
     */
    public function nextPlayerDataProvider()
    {
        $players = [
            $this->newMockPlayer('Jim', 5),
            $this->newMockPlayer('Ted', 4),
            $this->newMockPlayer('Bob', 3),
        ];
        return [
            [
                $players,
                [
                    $this->newTurn($players[0]),
                ],
                $players[1]
            ],
            'wrap around' => [
                $players,
                [
                    $this->newTurn($players[1]),
                    $this->newTurn($players[2]),
                ],
                $players[0]
            ],
        ];
    }

    /**
     * @test
     */
    public function nextPlayerReturnsFirstPlayerIfNoCurrentTurnIsSet()
    {
        $players = [
            $this->newMockPlayer('Jim', 5),
            $this->newMockPlayer('Ted', 4),
            $this->newMockPlayer('Bob', 3),
        ];
        //Setup
        $this->testObj->setPlayers($players);

        $expected = $players[0];
        $actual = $this->testObj->nextPlayer();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function nextPlayerReturnsNullIfNoPlayersAreInGame()
    {
        $this->assertNull($this->testObj->nextPlayer());
    }

    /**
     * @test
     */
    public function newTurnReturnsANewTurnWithPlayerAndActionForTurnsPlayer()
    {
        $players = [
            $this->newMockPlayer('Jim', 5),
            $this->newMockPlayer('Ted', 4),
            $this->newMockPlayer('Bob', 3),
        ];
        //Setup
        $this->testObj->setPlayers($players);
        $this->mockActionFactory->expects($this->once())->method('newAction')
            ->willReturn($this->newMockAction());
        $this->mockTurnFactory->expects($this->once())->method('newTurn')
            ->willReturn($this->newTurn());

        $expected_player = $players[0];
        $actual = $this->testObj->newTurn();

        $this->assertInstanceOf(TurnInterface::class, $actual);
        $this->assertEquals($expected_player, $actual->player());
        $this->assertEquals($expected_player, $actual->currentAction()->player());
    }

    /**
     * @test
     */
    public function newTurnReturnsNullIfNoPlayersAreInTheGame()
    {
        $this->assertNull($this->testObj->newTurn());
    }

    protected function newTurn(PlayerInterface $player = null)
    {
        $turn = new Turn(new \Aesonus\TurnGame\Storage\RuntimeTurnStorage());
        if (isset($player)) {
            $turn->setPlayer($player);
        }
        return $turn;
    }

    protected function newMockAction()
    {
        return $this->getMockForAbstractClass(AbstractAction::class);
    }

    protected function newMockPlayer($name = null, $initiative = null): MockObject
    {
        /* @var $player AbstractPlayer */
        $player = $this->getMockBuilder(AbstractPlayer::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMockForAbstractClass();
        if (isset($initiative)) {
            $player->setInitiative($initiative);
        }

        if (isset($name)) {
            $player->setName($name);
        }

        return $player;
    }
}
