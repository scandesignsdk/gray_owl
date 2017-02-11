<?php

/* IMPORTANT: 
 *
 * Since my dev enviroment is only using PHP 7.0 i had to remove some of 
 * the PHP 7.1 functionality such as visibility modifiers for 
 * constants.
 *
 */

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
     * @var Bracket[]
     */
    private $brackets;

     /**
     * @var bracket_group_size
     */
    private $bracket_group_size;

    /**
     * @param Player[] $players Array with players
     * @param Bracket_group_size[] $bracket_group_size Size of the bracket groups
     */
    public function __construct(array $players, int $bracket_group_size = 2 )
    {
        $this->players = $players;
        $this->bracket_group_size = $bracket_group_size;
    }

     /**
     * @param Group_number_offset[] $group_number_offset The group number offest.
     */
    private function generate_brackets(int $group_number_offset = 0)
    {

        $brackets = array();
        $bracket_group = $group_number_offset;

        foreach ($this->players as $key => $player) {

            // If the bracket group is full iterate to the next one
            if (isset($brackets[$bracket_group]) && count($brackets[$bracket_group]) == $this->bracket_group_size )
                $bracket_group++;

            
            $brackets[$bracket_group][] = $player;
                  
        }

        $this->brackets = $brackets;
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
       
        $valid_hands = array(self::ROCK, self::PAPER, self::SCISSOR);

        if (count($this->players) <= 1) {
            throw new CancelledTournamentException("Too few players to start a tournament");
        }

        foreach ($this->players as $player) {

            if (!in_array($player->getHand(), $valid_hands)) {
                throw new InvalidTournamentException("Error for player having an invalid hand");
            }
        }

        if ($this->simulate_rsp() && isset($this->players)) {

            return $this->players[0];    

        }
    }

     /**
     * Simulate the turnament
     *
     * @return Boolean
     */
    private function simulate_rsp() {

        $round = 1;
        while (count($this->players) > 1) {

            $this->generate_brackets();
            $round_winners = array();
            
            foreach ($this->brackets as $bracket_group) {

                if (count($bracket_group) == $this->bracket_group_size) {
                    if (    $bracket_group[0]->getHand() == self::PAPER && $bracket_group[1]->getHand() == self::ROCK    ||
                            $bracket_group[0]->getHand() == self::ROCK && $bracket_group[1]->getHand() == self::SCISSOR  ||
                            $bracket_group[0]->getHand() == self::SCISSOR && $bracket_group[1]->getHand() == self::PAPER ||
                            $bracket_group[0]->getHand() == $bracket_group[1]->getHand()    ) {
                    
                            $round_winners[] = $bracket_group[0];

                    } else {

                            $round_winners[] = $bracket_group[1];
                    }

                } else {

                    $round_winners[] = $bracket_group[0];
                }
            }

            $this->players = $round_winners; 
            $round++;
        }

        return TRUE;
        
    }

}