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
     * @param Player[] $players
     * @param PlayerController $playerCtrl
     * @param MatchController $matchCtrl
     */
    public function __construct( array $players, PlayerController $playerCtrl = null, MatchController $matchCtrl = null )
    {
        $this->playerCtrl = is_null( $playerCtrl ) ? new PlayerController( $players, self::HANDS ) : $playerCtrl;
        $this->matchCtrl = is_null( $matchCtrl ) ? new MatchController( self::HANDS ) : $matchCtrl;
    }

    /**
     * Start the tournament and get the winner.
     * @return Player
     */
    public function getWinner(): Player
    {
        try
        {
            // Set validated players
            $this->players = $this->playerCtrl->getValidPlayers();

            // Check if the tournament has enough validated players
            $this->isValidTournament();

            // Check all player hands
            $this->playerCtrl->validatePlayerHands( $this->players );

            // Run matched and get the final winner
            return $this->runMatches();

        }
        catch( InvalidTournamentException | CancelledTournamentException $e )
        {
            echo $e->getMessage();
        }
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
/*
require '../../vendor/autoload.php';

$players[] = new Player( "Iso", "R" );
$players[] = new Player( "Eda", "S" );
$players[] = new Player( "Elif", "S" );
$players[] = new Player( "Dilo", "R" );
$players[] = new Player( "Cam", "R" );
$players[] = new Player( "John", "R" );
$players[] = new Player( "Noo", "S" );
$players[] = new Player( "Noo2", "P" );
$players[] = new Player( "Noo3", "R" );

$rps = new RPSTournament( $players );

print_r( $rps->startTournament() );*/