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
        foreach ($this->players as $single_player) {
            if(!in_array(strtoupper($single_player->getHand()), array(self::ROCK, self::PAPER, self::SCISSOR))) {
                throw new InvalidTournamentException();
                break;
            }
        }

        if(count($this->players) <= 1) {
            throw new CancelledTournamentException();
        }
        else {
            // Start rounds
            return $this->round_plays($this->players);
        }            
    }

    /**
     * Conduct rounds of the tournament
     * @param Player[] $players Array with players
     *
     * @return Player
     */
    public function round_plays(array $players) : Player
    {
        $winner_arr = [];

        if(count($players) == 1) {
            // Winner here.
            return $players[0];
        }
        else {
            for($i = 0; $i < (count($players) - 1); $i+=2 ) {
                $winner_arr[] = $this->each_play($players[$i], $players[$i+1]);  
            }
            if(count($players) % 2) {
                // walk over for last single player.
                $winner_arr[] = $players[count($players) - 1]; 
            }
            return $this->round_plays($winner_arr);
        }
    }

    /**
     * Conduct each play between 2 players and return winner.
     * @param Player $player1
     * @param Player $player2     
     *
     * @return Player
     */
    public function each_play($player1, $player2) : Player
    {
        $win_combination = array(
                                    array(self::ROCK, self::SCISSOR),
                                    array(self::SCISSOR, self::PAPER),
                                    array(self::PAPER, self::ROCK)
                                );
        $p1_hand = strtoupper($player1->getHand());
        $p2_hand = strtoupper($player2->getHand());

        if($p1_hand == $p2_hand){
            return $player1;
        }

        if(in_array(array($p1_hand, $p2_hand), $win_combination)) {
           return $player1; 
        }
        else {
            return $player2;
        }   
    }
}
