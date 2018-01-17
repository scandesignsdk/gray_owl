<?php

namespace SDM\RPS;


class MatchController
{
    /**
     * @var array
     */
    private $hands;

    /**
     * @var Player[]
     */
    private $matchPlayers;

    /**
     * MatchController constructor.
     *
     * @param $hands
     */
    public function __construct( $hands )
    {
        $this->hands = $hands;
    }

    /**
     * Get the winner.
     *
     * @param $players
     *
     * @return Player
     */
    public function getWinner( $players ): Player
    {
        $this->setPlayers( $players );

        if( sizeof( $this->matchPlayers ) == 1 )
        {
            return $this->matchPlayers[ 0 ];
        }

        return $this->getMatchWinner();
    }

    /**
     * Set players
     *
     * @param $players
     */
    private function setPlayers( $players )
    {
        $this->matchPlayers = $players;
    }

    /**
     * Get the winner of the match
     * @return Player
     */
    private function getMatchWinner(): Player
    {
        $hands = $this->hands;
        $f = $this->matchPlayers[ 0 ];
        $s = $this->matchPlayers[ 1 ];

        foreach( $hands as $hand )
        {
            $next = next( $hands );
            $nextHand = !$next ? $hands[ 0 ] : $next;

            if( $f->getHand() === $hand && ( $s->getHand() === $hand || $s->getHand() === $nextHand ) )
            {
                return $f;
            }
        }

        return $s;
    }
}