<?php
namespace SDMTests\RPS;

use SDM\RPS\CancelledTournamentException;
use SDM\RPS\InvalidTournamentException;
use SDM\RPS\Player;
use SDM\RPS\RPSTournament;

class Test extends \PHPUnit_Framework_TestCase
{

    public function testPlayer()
    {
        $player = new Player('name', 'hand');
        $this->assertEquals('NAME', strtoupper($player->getName()));
        $this->assertEquals('HAND', strtoupper($player->getHand()));
    }

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
     * @dataProvider validMatchesProvider
     * @param Player[] $players
     * @param string|false $winner
     */
    public function testValidMatches($players, $winner)
    {
        $tournament = new RPSTournament($players);
        $this->assertEquals($winner, $tournament->getWinner()->getName());
    }

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
     * @dataProvider invalidMatchesProvider
     * @param Player[] $players
     */
    public function testInvalidMatches($players)
    {
        $this->expectException(InvalidTournamentException::class);
        $tournament = new RPSTournament($players);
        $tournament->getWinner();
    }

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
