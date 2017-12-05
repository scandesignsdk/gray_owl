<?php
namespace SDM\RPS;

use SDM\RPS\Player;
use SDM\RPS\InvalidTournamentException;
use SDM\RPS\CancelledTournamentException;

class RPSTournament
{

    private const ROCK = 'R';
    private const PAPER = 'P';
    private const SCISSOR = 'S';

    /**
     * @var Player[]
     */
    private $players;

    /**
     * @param Player[] $players Array with players
     */
    public function __construct(array $players)
    {
        $this->players = array_combine(array_map(function($p) {
            return $p->getName();
        }, $players), $players);
    }

    /**
     * Get the winner of the tournament
     *
     * @throws InvalidTournamentException
     * @throws CancelledTournamentException
     *
     * @return Player
     */
    public function getWinner(): Player
    {
        if (count($this->players) <= 1) {
            throw new CancelledTournamentException();
        }

        while (count($this->players) > 1) {
            $matches = $this->planMatches();
            
            foreach ($matches as $match) {
                $this->playRound($match);
            }
        }

        return current($this->players);
    }

    private function planMatches(): array
    {
        $matches = [];
        $noOfMatches = ceil(count($this->players) / 2);
        
        for ($noOfMatches; $noOfMatches > 0; $noOfMatches--) {
            $matches[] = array_splice($this->players, 0, 2);
        }

        return $matches;
    }

    private function playRound($match)
    {
        $winner = 'first';

        if (count($match) > 1) {
            $firstHand = strtoupper(current($match)->getHand());
            $secondHand = strtoupper(end($match)->getHand());

            if (! in_array($firstHand, [self::ROCK, self::PAPER, self::SCISSOR]) ||
                ! in_array($secondHand, [self::ROCK, self::PAPER, self::SCISSOR])) {
                throw new InvalidTournamentException();
            }
            
            reset($match);

            if ($firstHand !== $secondHand) {
                switch($firstHand.$secondHand) {
                    case self::ROCK.self::PAPER:
                    case self::PAPER.self::SCISSOR:
                    case self::SCISSOR.self::ROCK:
                        $winner = 'second';
                        break;
                }
            }
        }

        if ($winner === 'first') {
            $player = current($match);
        } else {
            $player = end($match);
        }

        $this->players[$player->getName()] = $player;
    }

}
