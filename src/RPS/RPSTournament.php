<?php
namespace SDM\RPS;

class RPSTournament 
{

    private const ROCK = 'R';
    private const PAPER = 'P';
    private const SCISSOR = 'S';
    private const VALID_HANDS = [self::ROCK, self::SCISSOR, self::PAPER];

    /**
     *
     * @var Round[]
     */
    private $tournament;

    /**
     *
     * @var Player
     */
    private $winner;

    /**
     *
     * @var Player
     */
    private $playerWithoutOpponent;

    /**
     * @var int
     */
    private $roundIndex;

    /**
     * @var Player[]
     */
    private $players;

    /**
     * @param Player[] $players Array with players
     */
    public function __construct(array $players) 
    {
        $this->players = $players;
        $this->tournament = [];
        $this->roundIndex = 0;

        if ($this->isTournamentInvalid()) {
            throw new InvalidTournamentException();
        }
        if (count($this->players) < 2) {
            throw new CancelledTournamentException();
        }
        $this->playMatches();
    }

    /**
     * Get the winner of the tournament
     *
     * @throws InvalidTournamentException
     * @throws CancelledTournamentException
     *
     * @return Player
     */
    public function getWinner() : Player 
    {
        return $this->_getWinner();
    }

    private function isTournamentInvalid() {
        foreach ($this->players as $player) {
            if (!in_array(strtoupper($player->getHand()), self::VALID_HANDS)) {
                return true;
            }
        }
        return false;
    }

    private function createFirstRound() {
        $numOfPlayers = count($this->players);
        $round = new Round();

        if ($numOfPlayers % 2 !== 0) {
            $players = array_slice($this->players, 0, -1);
            $playerWithoutOpponent = array_slice($this->players, -1);

            $matches = $this->createMatches($players);
            $round->setMatches($matches);
            $this->playerWithoutOpponent = $playerWithoutOpponent[0];
        } else {
            $round->setMatches($this->createMatches($this->players));
        }
        $this->tournament[] = $round;
    }

    private function createMatches($players) {
        $numOfPlayers = count($players);
        $matches = [];
        for ($index = 0; $index < $numOfPlayers; $index += 2) {
            $match = new Match();
            $match->setFirstPlayer($players[$index]);
            $match->setSecondPlayer($players[$index + 1]);
            $matches[] = $match;
        }
        return $matches;
    }

    private function createRound(array $matches) {
        $numOfMatches = count($matches);
        $round = new Round();

        if ($numOfMatches === 1) {
            $round->addMatch($matches[0]);
        } elseif ($numOfMatches % 2 !== 0) {
            $mtchs = array_slice($matches, 0, -1);
            $lastMatch = array_slice($matches, -1);
            $playerWithoutOpponent = $lastMatch[0]->getVinner();

            for ($index = 0; $index < count($mtchs); $index += 2) {
                $match = new Match();
                $match->setFirstPlayer($mtchs[$index]->getVinner());
                $match->setSecondPlayer($mtchs[$index + 1]->getVinner());
                $round->addMatch($match);
            }

            $m = new Match();
            $m->setFirstPlayer($playerWithoutOpponent);
            $m->setSecondPlayer($this->playerWithoutOpponent);
            $round->addMatch($m);
        } else {
            for ($index = 0; $index < $numOfMatches; $index += 2) {
                $match = new Match();
                $match->setFirstPlayer($matches[$index]->getVinner());
                $match->setSecondPlayer($matches[$index + 1]->getVinner());
                $round->addMatch($match);
            }
        }
        return $round;
    }

    private function playMatches() {
        $this->createFirstRound();

        while (($round = $this->getNextRound()) instanceof Round) {
            $matches = $round->getMatches();
            $count = count($matches);
            $this->calculateWinner($matches);

            if ($count < 2 && empty($this->playerWithoutOpponent)) {
                $this->tournament[] = 'TournamentFinished';
            } elseif ($count < 2) {
                $match = new Match();
                $match->setFirstPlayer($this->winner);
                $match->setSecondPlayer($this->playerWithoutOpponent);
                $this->playerWithoutOpponent = null;
                $this->tournament[] = $this->createRound([$match]);
            } else {
                $this->tournament[] = $this->createRound($matches);
            }
        }
    }

    private function calculateWinner($matches) {
        foreach ($matches as $match) {
            $firstPlayer = $match->getFirstPlayer();
            $secondPlayer = $match->getSecondPlayer();
            $firstPlayerHand = strtoupper($firstPlayer->getHand());
            $secondPlayerHand = strtoupper($secondPlayer->getHand());

            if ($firstPlayerHand === $secondPlayerHand) {
                $match->setVinner($firstPlayer);
            }
            if ($firstPlayerHand === self::ROCK && $secondPlayerHand === self::SCISSOR) {
                $match->setVinner($firstPlayer);
            }
            if ($firstPlayerHand === self::ROCK && $secondPlayerHand === self::PAPER) {
                $match->setVinner($secondPlayer);
            }
            if ($firstPlayerHand === self::SCISSOR && $secondPlayerHand === self::PAPER) {
                $match->setVinner($firstPlayer);
            }
            if ($firstPlayerHand === self::SCISSOR && $secondPlayerHand === self::ROCK) {
                $match->setVinner($secondPlayer);
            }
            if ($firstPlayerHand === self::PAPER && $secondPlayerHand === self::ROCK) {
                $match->setVinner($firstPlayer);
            }
            if ($firstPlayerHand === self::PAPER && $secondPlayerHand === self::SCISSOR) {
                $match->setVinner($secondPlayer);
            }
            $this->winner = $match->getVinner();
        }
    }

    private function getNextRound() {
        return $this->tournament[$this->roundIndex++];
    }

    private function _getWinner() {
        return $this->winner;
    }

}
