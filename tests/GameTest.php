<?php

namespace Aesonus\Tests;

use Aesonus\TurnGame\{
    AbstractPlayer,
    Game,
    AbstractAction
};
use Aesonus\TurnGame\Contracts\{
    TurnFactoryInterface,
    ActionFactoryInterface,
    PlayerInterface,
    TurnInterface
};

/**
 * Tests the game class
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class GameTest extends \Aesonus\TestLib\BaseTestCase
{

    public $testObj;
    public $playerQueue;
    public $mockTurnFactory;
    public $mockActionFactory;

    protected function setUp()
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
        $playerQueue = $this->getPropertyValue($this->testObj, 'players');
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
            [[$this->newMockPlayer(null, 1)], [1]],
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
        $this->assertArraySubset($find, $actual_names);
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
    public function findPlayersReturnsNullIfNoPlayersAreInGame()
    {
        $this->assertNull($this->testObj->findPlayers(['test']));
    }

    /**
     * @test
     * @dataProvider alreadyPresentTurnsDataProvider
     */
    public function setCurrentTurnPrependsTurnOntoTurnsProperty($already_present_turns)
    {
        $expected = $this->newTurn();
        //Put some dummy turns in
        $this->setPropertyValue($this->testObj, 'turns', $already_present_turns);

        $this->testObj->setCurrentTurn($expected);
        $actual = $this->getPropertyValue($this->testObj, 'turns');
        $this->assertEquals($expected, $actual[0]);
    }

    /**
     * Data Provider
     */
    public function alreadyPresentTurnsDataProvider()
    {
        return [
            [null],
            [[$this->newTurn()]],
            [[$this->newTurn(), $this->newTurn()]]
        ];
    }

    /**
     * @test
     */
    public function currentTurnGetsTheFirstElementInTurnsProperty()
    {
        $expected = $this->newTurn();
        $this->setPropertyValue($this->testObj, 'turns', [$expected, $this->newTurn()]);

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
        $this->setPropertyValue($this->testObj, 'turns', $turns);
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
                2,
                $expected
            ],
            [
                [
                    $expected,
                    $this->newTurn(),
                    $this->newTurn(),
                ],
                0,
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
        $this->setPropertyValue($this->testObj, 'turns', $turns);

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
        $this->setPropertyValue($this->testObj, 'turns', $turns);
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
    public function isStartedReturnsTrueIfTurnsPropertyIsNotEmpty()
    {
        $turns = [
            $this->newTurn(),
            $this->newTurn(),
            $this->newTurn(),
        ];
        $this->setPropertyValue($this->testObj, 'turns', $turns);
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
        $this->setPropertyValue($this->testObj, 'players', $players);
        $this->setPropertyValue($this->testObj, 'turns', $turns);

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
                    $this->newTurn($players[2]),
                    $this->newTurn($players[1]),
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
        $this->setPropertyValue($this->testObj, 'players', $players);

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
        $this->setPropertyValue($this->testObj, 'players', $players);
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
        $turn = new \Aesonus\TurnGame\Turn(new \SplStack());
        if (isset($player)) {
            $turn->setPlayer($player);
        }
        return $turn;
    }

    protected function newMockAction()
    {
        return $this->getMockForAbstractClass(AbstractAction::class);
    }

    protected function newMockPlayer($name = null, $initiative = null): \PHPUnit_Framework_MockObject_MockObject
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
