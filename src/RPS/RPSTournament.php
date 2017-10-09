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
        //Checks if tournament can be played.
        if(sizeof($this->players) <= 1){
            throw new CancelledTournamentException("Not enough players!");
        }
        
        //Checks if tournament is invalid.
        foreach($this->players as $player){
            
            if(! ($player->getHand() == 'R' || 
                  $player->getHand() == 'P' || 
                  $player->getHand() == 'S' ) 
              ) throw new InvalidTournamentException("Hand must be either 'R', 'P' or 'S'.");
        }
        
        //Run the tournamentWinner function
        $winner = RPSTournament::tournamentWinner($this->players);
        
        return $winner;
    }    
    
    /**
     * Returns winner of the tournament
     * 
     * @param Player[] $players Array with players
     *
     * @return Player
     */
    private static function tournamentWinner(array $players) : Player
    {
        //Run while size of $players is bigger than 1.
        while(sizeof($players) > 1) {
            
            //Array used for storing winners advancing to the next round
            $tempPlayers = array();
            
            //Looping over $players array while matching up players in pairs
            for($i = 0; $i < count($players)-1; $i+=2){
                $tempPlayers[] = RPSTournament::matchWinner($players[$i], $players[$i+1]);
            }
            
            //If the number of players is uneven, the last player
            //will automatically be sent to the next round.
            if(count($players) % 2 == 1)
                $tempPlayers[] = end($players);
            
            //Update the $players array with all the winners from the round.
            $players = $tempPlayers;
        }
        
        //Return the $players array(now only containing the winner)
        return end($players);
    }
    
    /**
     * Returns winner of the RPS match
     *
     * @param Player $player1 Player object
     * @param Player $player2 Player object
     *
     * @return Player
     */
    private static function matchWinner($player1, $player2)
    {
                
        //Tie - Player1 wins
        if($player1->getHand() == $player2->getHand()){
            return $player1;
        }
        
        //Player1 choose Rock
        elseif($player1->getHand() == 'R'){
            if($player2->getHand() == 'P'){
                return $player2;
            
            } else {
                return $player1;
            }  
        }
        
        //Player1 choose Paper
        elseif($player1->getHand() == 'P'){
            if($player2->getHand() == 'S'){
                return $player2;
            
            } else {
                return $player1;
            }
        }
        
        //Player1 choose Scissors
        elseif($player1->getHand() == 'S'){
            if($player2->getHand() == 'R'){
                return $player2;
                
            } else {
                return $player1;
            }
        }
    }
}
