<?php
namespace SDM\RPS;
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
        throw new CancelledTournamentException("There should be at least two players, so tournament is cancelled");
      }
      if (!$this->validatePlayerHand()) {
        throw new InvalidTournamentException("The tournament is not valid");
      }
      return $this->getTournamentWinner($this->players);
    }

    private function validatePlayerHand() : bool
    {
      foreach ($this->players as $player) {
        if (!$this->isHandValid($player->getHand())) {
          return False;
        }
      }
      return True;
    }

    private function isHandValid(string $hand): bool
    {
      $arr = array(self::ROCK, self::PAPER, self::SCISSOR);
      return in_array(strtoupper($hand), $arr);
    }

    private function getTournamentWinner(array $players) : Player
    {
      if (count($players) == 1) {
        return $players[0];
      }
      $nextRoundPlayers = array();
      $nextRoundPlayers = $this->getNextRoundPlayers($players);
      return $this->getTournamentWinner($nextRoundPlayers);
    }

    private function getNextRoundPlayers(array $players)
    {
      $nextRoundPlayers = array();
      foreach (array_chunk($players, 2) as $pair) {
        if (count($pair) == 2) {
          if ($this->compareHands($pair[0]->getHand(), $pair[1]->getHand())) {
            array_push($nextRoundPlayers, $pair[0]);
          } else {
            array_push($nextRoundPlayers, $pair[1]);
          }
        } else {
          array_push($nextRoundPlayers, $pair[0]);
        }
      }
      return $nextRoundPlayers;
    }

    private function compareHands($hand1, $hand2): bool
    {
      $hand1 = strtoupper($hand1);
      $hand2 = strtoupper($hand2);
      if ($hand1 == self::ROCK) {
        if ($hand2 == self::PAPER) {
          return False;
        } else {
          return True;
        }
      } else if ($hand1 == self::PAPER) {
        if ($hand2 == self::SCISSOR) {
          return False;
        } else {
          return True;
        }
      } else if ($hand1 == self::SCISSOR) {
        if ($hand2 == self::ROCK) {
          return False;
        } else {
          return True;
        }
      }
    }



}
