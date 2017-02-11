<?php
namespace SDM\RPS;

class RPSTournament
{

    const ROCK = 'R';
    const PAPER = 'P';
    const SCISSOR = 'S';

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
        if (count($this->players) <= 1) {
            throw new CancelledTournamentException();
        } else {
            $validValues = array(self::ROCK, self::PAPER, self::SCISSOR);

            foreach ($this->players as $player) {
                $hand = $player->getHand();
                if (!in_array($hand, $validValues)) {
                    throw new InvalidTournamentException();
                }
            }
            return $this->roundCompare($this->players);
        }

    }

    /**
     * Compare all players in rounds
     * @param array $players
     * @return Player winner
     */
    private function roundCompare(array $players)
    {
        $winners = array();

        // first round
        $totalPlayer = count($players);

        // game begin
        for ($i = 0; $i <= $totalPlayer - 2; $i = $i + 2) {
            $playerOne = $players[$i];
            $playerTwo = $players[$i + 1];
            $winners[] = $this->compare($playerOne, $playerTwo);
        }

        // if there has last one player, he will join next round
        if (isset($players[$i])) {
            $winners[] = $players[$i];
        }

        if (count($winners) != 1) {
            // next round
            return $this->roundCompare($winners);
        } else {
            // until get winner
            return $winners[0];
        }

    }

    /**
     * Compare two players
     * @param Player $playerOne
     * @param Player $playerTwo
     * @return Player
     */
    private function compare(Player $playerOne, Player $playerTwo)
    {
        $r = self::ROCK;
        $p = self::PAPER;
        $s = self::SCISSOR;

        $handOne = strtoupper($playerOne->getHand());
        $handTwo = strtoupper($playerTwo->getHand());

        // if same hands, first one win
        if ($handOne == $handTwo)
            return $playerOne;
        else {
            $compare = array($handOne, $handTwo);
            $winMethods = array(array($s, $p), array($r, $s), array($p, $r));

            // if in win methods array, means first player win
            if (in_array($compare, $winMethods)) {
                return $playerOne;
            } else {
                return $playerTwo;
            }

        }

    }

}
