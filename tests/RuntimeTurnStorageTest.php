<?php

namespace Aesonus\Tests;

use Aesonus\TestLib\BaseTestCase;

/**
 * Description of RuntimeTurnStorageTest
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class RuntimeTurnStorageTest extends BaseTestCase
{
    public $testObj;

    protected function setUp(): void
    {
        $this->testObj = new \Aesonus\TurnGame\Storage\RuntimeTurnStorage();
        $this->testObj->deleteAllTurns();
    }

    /**
     * @test
     */
    public function setTurnIdReturnsFalseIfTurnIdDoesNotExist()
    {
        $actual = $this->testObj->loadRecord(0);
        $this->assertFalse($actual);
    }

    /**
     * @test
     * @dataProvider getActionStackDataProvider
     */
    public function getActionStackReturnsActionStackForRecordId($turns, $expected_id)
    {
        //Create some turns
        foreach ($turns as $turn) {
            $storage = $this->testObj->newInstance();
            foreach ($turn as $method => $attr) {

                call_user_func([$storage, '__set' . $method], $attr);
            }
            $storage->save();
        }
        $this->testObj->loadRecord($expected_id);
        $actual = $this->testObj->__getActionStack();
        $expected = $turns[$expected_id]['ActionStack'];
        $this->assertSame($expected, $actual);
    }

    /**
     * Data Provider
     */
    public function getActionStackDataProvider()
    {
        $current_player = 'CurrentPlayer';
        $action_stack = 'ActionStack';
        return [
            [
                [
                    [
                        $current_player => $this->newMockPlayer(),
                        $action_stack => new \SplStack,
                    ], [
                        $current_player => $this->newMockPlayer(),
                        $action_stack => new \SplStack,
                    ]
                ],
                1
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getCurrentPlayerDataProvider
     */
    public function getCurrentPlayerReturnsActionStackForRecordId($turns, $expected_id)
    {
        //Create some turns
        foreach ($turns as $turn) {
            $storage = $this->testObj->newInstance();
            foreach ($turn as $method => $attr) {

                call_user_func([$storage, '__set' . $method], $attr);
            }
            $storage->save();
        }
        $this->testObj->loadRecord($expected_id);
        $actual = $this->testObj->__getCurrentPlayer();
        $expected = $turns[$expected_id]['CurrentPlayer'];
        $this->assertSame($expected, $actual);
    }

    /**
     * Data Provider
     */
    public function getCurrentPlayerDataProvider()
    {
        $current_player = 'CurrentPlayer';
        $action_stack = 'ActionStack';
        return [
            [
                [
                    [
                        $current_player => $this->newMockPlayer(),
                        $action_stack => new \SplStack,
                    ], [
                        $current_player => $this->newMockPlayer(),
                        $action_stack => new \SplStack,
                    ]
                ],
                1
            ],
        ];
    }

    protected function newMockPlayer(): \PHPUnit\Framework\MockObject\MockObject
    {
        $builder = $this->getMockBuilder(\Aesonus\TurnGame\AbstractPlayer::class);
        $builder->disableOriginalConstructor();
        return $builder->getMockForAbstractClass();
    }

    /**
     * @test
     */
    public function saveReturnsTheTurnId()
    {
        $expected = 0;
        $actual = $this->testObj->save();
        $this->assertEquals($expected, $actual);
    }
}
