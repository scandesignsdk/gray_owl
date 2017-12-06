<?php
namespace SDMTests\RPS;

use SDM\RPS\Player;

class PlayerTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * Tests the constructor and getter functions of the Player class
	 *
	 * @return void
	 */
    public function testPlayerGetters()
    {
        $player = new Player('name', 'hand');
        $this->assertEquals('name', $player->getName());
        $this->assertEquals('HAND', $player->getHand());
    }

	/**
	 * Tests the exception thrown when the player has no name
	 *
	 * @dataProvider emptyProvider()
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage All players must have names
	 * @expectedExceptionCode 10001
	 */
	public function testEmptyName($name) : void
	{
		$player = new Player($name, 'hand');
	}

	/**
	 * Provides empty values for the hands and the names
	 */
	public function emptyProvider() : array
	{
		return [
			[''],
			[' '],
			["\t"],
			["\n"]
		];
	}

	/**
	 * Tests the constructor of the player class when the hand provided is empty
	 *
	 * @dataProvider emptyProvider()
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage All players must have hands
	 * @expectedExceptionCode 10002
	 */
	public function testEmptyHand() : void
	{
		$player = new Player('name', '');
	}
}
