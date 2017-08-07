<?php

namespace SDM\RPS;

class Match {

    /**
     *
     * @var Player 
     */
    private $firstPlayer;

    /**
     *
     * @var Player
     */
    private $secondPlayer;

    /**
     *
     * @var Player
     */
    private $vinner;

    function __construct() {
        
    }

    public function getFirstPlayer(): Player {
        return $this->firstPlayer;
    }

    public function getSecondPlayer(): Player {
        return $this->secondPlayer;
    }

    public function setFirstPlayer(Player $firstPlayer) {
        $this->firstPlayer = $firstPlayer;
    }

    public function setSecondPlayer(Player $secondPlayer) {
        $this->secondPlayer = $secondPlayer;
    }

    public function getVinner(): Player {
        return $this->vinner;
    }

    public function setVinner(Player $vinner) {
        $this->vinner = $vinner;
    }

}
