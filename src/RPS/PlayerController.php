<?php

namespace SDM\RPS;


class PlayerController
{
    /**
     * @var array
     */
    private $players;

    /**
     * @var array
     */
    private $hands;

    /**
     * PlayerController constructor.
     *
     * @param $players
     * @param $hands
     */
    public function __construct( $players, $hands )
    {
        $this->players = $players;
        $this->hands = $hands;
    }

    /**
     * Get validated players.
     * @return Player[]
     * @throws CancelledTournamentException
     */
    public function getValidPlayers()
    {
        $this->setValidPlayers();

        if( sizeof( $this->players ) > 1 )
        {
            return $this->players;
        }

        throw new CancelledTournamentException();
    }

    /**
     * @param Player $player
     *
     * @return bool
     * @throws InvalidTournamentException
     */
    public function validatePlayerHand( Player $player )
    {
        if( !in_array( $player->getHand(), $this->hands ) )
        {
            throw new InvalidTournamentException();
        }

        return true;
    }

    /**
     * @param $players
     *
     * @throws InvalidTournamentException
     */
    public function validatePlayerHands( $players )
    {
        foreach( $players as $player )
        {
            $this->validatePlayerHand( $player );
        }
    }

    /**
     * @return array
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * Set players array, only players which is Player object.
     */
    private function setValidPlayers()
    {
        $filterList = [];

        foreach( $this->players as $player )
        {
            if( $player instanceof Player )
            {
                $filterList[] = $player;
            }
        }

        $this->players = $filterList;
    }
}