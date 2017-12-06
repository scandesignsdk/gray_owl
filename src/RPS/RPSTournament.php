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
		$this->validateTournament();
		return $this->runRound($this->players);
    }

	/**
	 * Checks whether a players hand is valid or not
	 */
	public function isValidHand(string $hand) : bool
	{
		$hand = strtoupper($hand);
		return $hand == self::ROCK || $hand == self::PAPER || $hand == self::SCISSOR;
	}

	/**
	 * Checks whether the tournament is valid
	 */
	public function validateTournament()
	{
		if (count($this->players) < 2) {
			throw new CancelledTournamentException("Tournamnet must have at least 2 players");
		}

		foreach ($this->players as $_player) {
			if (!$this->isValidHand($_player->getHand())) {
				throw new InvalidTournamentException(sprintf('Player %s has invalid hand', $_player->getName()));
			}
		}
	}

	/**
	 * Checks whether player one is a winner
	 */
	public function isPlayerOneWinner(Player $player1, Player $player2) : bool
	{
		$hand1 = $player1->getHand();
		$hand2 = $player2->getHand();

		if ($hand1 == $hand2) {
			return true;
		}

		switch ($hand1) {
			case self::PAPER: $return = $hand2 == self::ROCK; break;
			case self::ROCK: $return = $hand2 == self::SCISSOR; break;
			case self::SCISSOR: $return = $hand2 == self::PAPER; break;
		}

		return $return;
	}

	/**
	 * Recursively run through the rounds until there is only one winner
	 */
	public function runRound($players) : Player
	{
		$playersQualifiedNextRound = [];

		$matches = array_chunk($players, 2);
		foreach($matches as $_match) {
			$playersQualifiedNextRound[] = count($_match) == 1 || $this->isPlayerOneWinner($_match[0], $_match[1]) ? $_match[0] : $_match[1];
		}

		if (count($playersQualifiedNextRound) == 1) {
			return $playersQualifiedNextRound[0];
		}

		return $this->runRound($playersQualifiedNextRound);
	}
}
