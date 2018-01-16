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
    private $players;

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
    public function getWinner( $players )
    {
        $this->setPlayers( $players );

        if( sizeof( $this->players ) == 1 )
        {
            return $this->players[ 0 ];
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
        $this->players = $players;

    }

    /**
     * Get the winner of the match
     * @return Player
     */
    private function getMatchWinner()
    {
        $hands = $this->hands;
        $f = $this->players[ 0 ];
        $s = $this->players[ 1 ];

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

        /*for( $i = 0; $i < sizeof( $hands ); $i++ )
        {
            $next = $i == sizeof( $hands ) - 1 ? 0 : $i + 1;

            if( $f->getHand() === $hands[ $i ] && ( $s->getHand() === $hands[ $i ] || $s->getHand() === $hands[ $next ] ) )
            {
                return $f;
            }
        }

        return $s;*/
    }
}