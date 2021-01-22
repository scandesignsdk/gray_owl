<?php

namespace SDM\RPS;

class RPSTournament
{
    private const ROCK = 'R';
    private const PAPER = 'P';
    private const SCISSOR = 'S';

    /**
     * @var Player[]
     */
    private array $players;

    /**
     * @param Player[] $players Array with players
     */
    public function __construct(array $players)
    {
        $this->players = $players;
    }

    /**
     * Get the winner of the tournament.
     *
     * @throws InvalidTournamentException
     * @throws CancelledTournamentException
     */
    public function getWinner(): Player
    {
    }
}
