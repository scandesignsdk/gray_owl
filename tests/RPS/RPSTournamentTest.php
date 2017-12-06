<?php
namespace SDMTests\RPS;

use SDM\RPS\CancelledTournamentException;
use SDM\RPS\InvalidTournamentException;
use SDM\RPS\Player;
use SDM\RPS\RPSTournament;

class RPSTournamentTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Tests the function which decides if the first player is the winner
	 *
	 * @dataProvider isPlayerOneWinnerProvider
	 * @param Player $player1
	 * @param Player $player2
	 * @param bool $outcome
	 */
	public function testIsPlayerOneWinner(Player $player1, Player $player2, bool $outcome)
	{
		$Tournament = new RPSTournament([$player1, $player2]);
		$this->assertEquals($outcome, $Tournament->isPlayerOneWinner($player1, $player2));
	}

	/**
	 * Provides data for testIsPlayerOneWinner()
	 *
	 * @return array
	 */
	public function isPlayerOneWinnerProvider() : array
	{
		return [
			[new Player('one', 'P'), new Player('one', 'P'), true],
			[new Player('one', 'P'), new Player('one', 'R'), true],
			[new Player('one', 'P'), new Player('one', 'S'), false],
			[new Player('one', 'R'), new Player('one', 'P'), false],
			[new Player('one', 'R'), new Player('one', 'R'), true],
			[new Player('one', 'R'), new Player('one', 'S'), true],
			[new Player('one', 'S'), new Player('one', 'P'), true],
			[new Player('one', 'S'), new Player('one', 'R'), false],
			[new Player('one', 'S'), new Player('one', 'S'), true],
		];
	}

	/**
	 * Tests the isValidHand() function
	 *
	 * @return void
	 */
	public function testIsValidHand()
	{
		$Tournament = $this->getDummyTournament();
		$this->assertTrue($Tournament->isValidHand('r'));
		$this->assertTrue($Tournament->isValidHand('s'));
		$this->assertTrue($Tournament->isValidHand('p'));
		$this->assertTrue($Tournament->isValidHand('R'));
		$this->assertTrue($Tournament->isValidHand('S'));
		$this->assertTrue($Tournament->isValidHand('P'));
		$this->assertFalse($Tournament->isValidHand('Djibburish'));
		return;
	}


	/**
	 * Provides a dummy tournament test object for
	 * faster development of methods that do not
	 * depend on the players array
	 *
	 * @return RPSTournament
	 */
	protected function getDummyTournament() : RPSTournament
	{
		return new RPSTournament([new Player('dummy1', 'R'), new Player('dummy1', 'S')]);
	}

	/**
	 * Provides arrays with valid tournament constructs
	 *
	 * @return array
	 */
	public function validMatchesProvider() : array
    {
        return [
            [
                [
                    new Player('Burgess', 'R'),
                    new Player('Clyde', 'R')
                ],
                'Burgess'
            ],
            [
                [
                    new Player('Aspen', 's'),
                    new Player('Ginger', 'p'),
                    new Player('Clyde', 'P'),
                    new Player('Carter', 'R'),
                    new Player('Blaze', 'R'),
                    new Player('Daffodil', 'S'),
                    new Player('Harmony', 'R'),
                    new Player('Chandler', 'S'),
                    new Player('Basil', 'R'),
                    new Player('Coral', 'S'),
                    new Player('Clive', 'P'),
                    new Player('Alma', 'R'),
                    new Player('Calvert', 'R'),
                ],
                'Clive'
            ],
            [
                [
                    new Player('Adam', 'P'),
                    new Player('Andrew', 'S'),
                    new Player('Chris', 'r'),
                    new Player('Casey', 'P'),
                    new Player('Cadman', 'R')
                ],
                'Cadman'
            ]
        ];
    }

    /**
	 * Tests the outcomes from the valid matches
	 *
     * @dataProvider validMatchesProvider
     * @param Player[] $players
     * @param string|false $winner
	 * @return void
     */
    public function testValidMatches($players, $winner)
    {
        $tournament = new RPSTournament($players);
        $this->assertEquals($winner, $tournament->getWinner()->getName());
    }

	/**
	 * Provides arrays with players some of whom have invalid hands
	 *
	 * @return array
	 */
    public function invalidMatchesProvider(): array
    {
        return [
            [
                [
                    new Player('John', 'R'),
                    new Player('Jane', 'E'),
                    new Player('Smith', 'B'),
                    new Player('Mike', 'D')
                ],
            ],
            [
                [
                    new Player('John', 'R'),
                    new Player('Jane', 'E'),
                ]
            ]
        ];
    }

    /**
	 * Tests the exception thrown when there is an invalid hand are invalid
	 *
     * @dataProvider invalidMatchesProvider
     * @param Player[] $players
     */
    public function testInvalidMatches($players) : void
    {
        $this->expectException(InvalidTournamentException::class);
        $tournament = new RPSTournament($players);
        $tournament->getWinner();
    }

	/**
	 * Provides array with invalid players arrays
	 *
	 * @return array
	 */
    public function cancelledMatchesProvider(): array
    {
        return [
            [
                [new Player('John', 'R')],
            ],
            [
                []
            ]
        ];
    }

    /**
	 * Test the the cancellation of tournaments because of
	 * bad players arrays
	 *
     * @dataProvider cancelledMatchesProvider
     * @param Player[] $players
     */
    public function testCancelTournament($players)
    {
        $this->expectException(CancelledTournamentException::class);
        $tournament = new RPSTournament($players);
        $tournament->getWinner();
    }
}
