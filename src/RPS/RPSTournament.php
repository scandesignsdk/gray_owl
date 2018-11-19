<?php
namespace SDM\RPS;

class RPSTournament
{
    
    //These are the valid throws
    private const ROCK = 'R';
    private const PAPER = 'P';
    private const SCISSOR = 'S';

    /**
     * @var Player[]
     * array of all players in the tournement
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
        //There are 3 valid throws.. 
        $aValid_throws = array(self::ROCK, self::PAPER, self::SCISSOR);
        
        //CancelledTournamentException must return a message
        if (count($this->players) <= 1) {
            //There is less than two players in this tournament
            throw new CancelledTournamentException("You can't have a tournemant with less than 2 players..");
        }
        
        //InvalidTournamentException must return a message
        foreach ($this->players as $playerKey => $player) {
            if (!in_array($player->getHand(), $aValid_throws)) {
                throw new InvalidTournamentException("Player tried to cheat with something other than Rock, Paper or Scissor... maybe Lava");
            }
        }
        
        //We need to return a player
        //So now we need to figure out who won..
        //Depending on how many players there are - we need to figure out how many fights we need.
        $fights = $this->createFights();
        $winner = $this->runTournement($fights);
        
        //print_r($winner);
        return $winner[0];
        
    }

   
    
    public function createFights($givenArray = array()){
        //we need two persons for each fight.
        //lets devide the players into 2ManGroups
        if($givenArray){
            //print_r('ARRAY GIVEN');
            $fights = array_chunk($givenArray, 2);
        } else {
            //print_r('USING ALL PLAYERS');
            $fights = array_chunk($this->players, 2);
        }
        $numberOfFights = count($fights);
        
        return $fights;
        
    }
    
    public function runTournement($fights = array()){
       
        $winners = array();
        
        if ($fights) {
            
            foreach ($fights as $fightsKey => $singleFight) {
               // testing
                //print_r($singleFight);
                
                if(count($singleFight) > 1){
                    
                    //There is more than one contender in this fight
                    $playerOneHand = $singleFight[0]->getHand();
                    $playerTwoHand = $singleFight[1]->getHand();
                    
                    //Defalt winner is the first fighter
                    $winnerOfFight = $singleFight[0];
                    
                    if ($playerOneHand == $playerTwoHand) {
                        //They have the same hand - PlayerOne wins
                        array_push($winners,$winnerOfFight);
                        //Testing
                        //print_r($winnerOfFight);
                        continue;
                    }
                    
                    switch ([$playerOneHand, $playerTwoHand]) {
                        case ['R', 'P']:
                            $winnerOfFight = $singleFight[1];
                            break;
                        case ['R', 'S']:
                            $winnerOfFight = $singleFight[0];
                            break;
                            
                        case ['P', 'S']:
                            $winnerOfFight = $singleFight[1];
                            break;
                        case ['P', 'R']:
                            $winnerOfFight = $singleFight[0];
                            break;
                            
                        case ['S', 'R']:
                            $winnerOfFight = $singleFight[1];
                            break;
                            
                        case ['S', 'P']:
                            $winnerOfFight = $singleFight[0];
                            break;
                   
                 
                        default : $winnerOfFight = $singleFight[0];
                    }
                    
                    
                } else {
                    //Defalt winner is the first fighter
                    //He is fighting alone - just let him win.
                    $winnerOfFight = $singleFight[0];
                    
                    
                    
                } // end of $singleFight > 1
                array_push($winners,$winnerOfFight);
                //testing
                //print_r($winnerOfFight);
                
            } //end of foreach fight
        }//end of if $fights
        
        //testing
        //print_r($winners);
       
        if(is_array($winners) && sizeof($winners) > 1){
            //IT IS AN ARRAY;
            // print_r(sizeof($winners));
           $nextRound = $this->createFights($winners);
           //print_r($nextRound);
           return $this->runTournement($nextRound);           
        } else {
            //NOT AN ARRAY;
            return $winners;

        }

    }
    
    //End of class
}


