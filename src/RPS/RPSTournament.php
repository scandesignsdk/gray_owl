<?php

namespace SDM\RPS;

use phpDocumentor\Reflection\Types\Self_;

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
     *
     * @throws CancelledTournamentException
     */
    public function __construct( array $players )
    {
        $this->players = $players;

        $this->isValidTournament();
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
        return null;
    }

    /**
     * Get players array.
     *
     * @return Player[]
     */
    public function getPlayers(): array
    {
        return $this->players;
    }

    /**
     * Check for invalid hands., if invalid
     * check winner hand. If the first wins then return 1,
     * if second wins then return 2, otherwise throw an InvalidTournamentException.
     *
     * @param $hand_1
     * @param $hand_2
     *
     * @return int
     * @throws InvalidTournamentException
     */
    public function winnerHand( $hand_1, $hand_2 )
    {
        if( $this->isValidHand( $hand_1 ) && $this->isValidHand( $hand_2 ) )
        {
            $checkForRock = $hand_1 === self::ROCK && ( $hand_2 === self::ROCK || $hand_2 === self::SCISSOR );
            $checkForScissor = $hand_1 === self::SCISSOR && ( $hand_2 === self::SCISSOR || $hand_2 === self::PAPER );
            $checkForPaper = $hand_1 === self::PAPER && ( $hand_2 === self::PAPER || $hand_2 === self::ROCK );

            return ( $checkForRock || $checkForScissor || $checkForPaper ) ? 1 : 2;
        }

        throw new InvalidTournamentException();
    }


    /**
     * Validate the size of players array, if 1 or less players throw an CancelledTournamentException.
     *
     * @throws CancelledTournamentException
     */
    private function isValidTournament()
    {
        if( isset( $this->players ) && is_array( $this->players ) && sizeof( $this->players ) <= 1 )
        {
            throw new CancelledTournamentException();
        }
    }

    /**
     * Validate the player's hand.
     *
     * @param $hand
     *
     * @return bool
     */
    private function isValidHand( $hand ): bool
    {
        return in_array( $hand,
                         [ self::ROCK,
                           self::SCISSOR,
                           self::PAPER ] );
    }

}