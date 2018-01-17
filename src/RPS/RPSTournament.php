<?php

namespace SDM\RPS;


class RPSTournament
{
    private const ROCK = 'R';
    private const SCISSOR = 'S';
    private const PAPER = 'P';

    private const HANDS = [ self::ROCK,
                            self::SCISSOR,
                            self::PAPER ];

    /**
     * @var Player[]
     */
    private $players;

    /**
     * @var PlayerController
     */
    private $playerCtrl;

    /**
     * @var MatchController
     */
    private $matchCtrl;

    /**
     * RPSTournament constructor.
     *
     * @param array $players
     * @param PlayerController|null $playerCtrl
     * @param MatchController|null $matchCtrl
     *
     */
    public function __construct( array $players, PlayerController $playerCtrl = null, MatchController $matchCtrl = null )
    {
        $this->playerCtrl = is_null( $playerCtrl ) ? new PlayerController( $players, self::HANDS ) : $playerCtrl;
        $this->matchCtrl = is_null( $matchCtrl ) ? new MatchController( self::HANDS ) : $matchCtrl;
        $this->players = $players;
    }

    /**
     * Start the tournament and get the winner.
     * @return Player
     * @throws CancelledTournamentException
     * @throws InvalidTournamentException
     */
    public function getWinner(): Player
    {
        // Set validated players
        $this->setValidPlayers();

        // Check all player hands
        $this->playerCtrl->validatePlayerHands( $this->players );

        // Check if the tournament has enough validated players
        $this->isValidTournament();

        // Run matched and get the final winner
        return $this->runMatches();
    }

    /**
     * @throws CancelledTournamentException
     */
    private function setValidPlayers()
    {
        $this->players = $this->playerCtrl->getValidPlayers();
    }

    private function runMatches()
    {
        while( sizeof( $this->players ) > 1 )
        {
            $list = [];

            while( sizeof( $this->players ) > 0 )
            {
                // Get match winner
                $list[] = $this->matchCtrl->getWinner( $this->getMatchPlayers() );

                // Unset match players from players array
                $this->unsetMatchPlayers();
            }

            $this->players = $list;
        }

        return $this->players[ 0 ];
    }

    private function getMatchPlayers()
    {
        if( isset( $this->players[ 1 ] ) )
        {
            return [ $this->players[ 0 ],
                     $this->players[ 1 ] ];
        }

        return [ $this->players[ 0 ] ];
    }

    private function unsetMatchPlayers()
    {
        if( sizeof( $this->getMatchPlayers() ) > 1 )
        {
            unset( $this->players[ 0 ], $this->players[ 1 ] );
        }
        else
        {
            unset( $this->players[ 0 ] );
        }

        // Re-index players array
        $this->players = array_values( $this->players );
    }

    /**
     * @return bool
     * @throws CancelledTournamentException
     */
    private function isValidTournament()
    {
        if( sizeof( $this->players ) > 1 )
        {
            return true;
        }

        throw new CancelledTournamentException();
    }
}