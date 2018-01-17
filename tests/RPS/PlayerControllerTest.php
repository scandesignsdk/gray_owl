<?php

namespace SDM\RPS;

use PHPUnit\Framework\TestCase;


class PlayerControllerTest extends TestCase
{

    /**
     * @var PlayerController
     */
    protected $playerCtrl;

    /**
     * @var Player[]
     */
    protected $players;

    /**
     * @var array
     */
    protected $hands;

    protected function setUp()
    {
        $this->hands = [ 'R',
                         'S',
                         'P' ];

        $this->players[] = new Player( "Adam", "P" );
        $this->players[] = new Player( "Andrew", "S" );
        $this->players[] = new Player( "Chris", "r" );
        $this->players[] = new Player( "Casey", "P" );
        $this->players[] = new Player( "Cadman", "R" );
        $this->players[] = null;

        $this->playerCtrl = new PlayerController( $this->players, $this->hands );
    }

    /**
     * Testing private functions
     *
     * @param $object
     * @param $methodName
     * @param array $parameters
     *
     * @return mixed
     */
    public function invokeMethod( &$object, $methodName, array $parameters = array() )
    {
        $reflection = new \ReflectionClass( get_class( $object ) );
        $method = $reflection->getMethod( $methodName );
        $method->setAccessible( true );

        return $method->invokeArgs( $object, $parameters );
    }


    /**
     * Is elements in the players array instance of Player object.
     * @throws CancelledTournamentException
     */
    public function testGetValidPlayers()
    {
        $this->assertEquals( 5, sizeof( $this->playerCtrl->getValidPlayers() ) );
    }

    /**
     * Is elements in the players array instance of Player object.
     * @throws CancelledTournamentException
     */
    public function testGetValidPlayers_no_players()
    {
        $this->expectException( CancelledTournamentException::class );

        $playerCtrl = new PlayerController( [], $this->hands );

        $this->assertEquals( null, sizeof( $playerCtrl->getValidPlayers() ) );
    }

    /**
     * Test not valid player hand.
     * @throws InvalidTournamentException
     */
    public function testValidatePlayerHand()
    {
        $this->expectException( InvalidTournamentException::class );

        $this->playerCtrl->validatePlayerHand( $this->players[ 2 ] );
    }

    /**
     * Test not valid player hands.
     * @throws InvalidTournamentException
     */
    public function testValidatePlayerHands_no_valid()
    {
        $this->expectException( InvalidTournamentException::class );

        $this->playerCtrl->validatePlayerHands( $this->players );
    }

    /**
     * Test not valid player hands.
     * @throws InvalidTournamentException
     */
    public function testValidatePlayerHands_valid()
    {
        $players[] = new Player( "Adam", "P" );
        $players[] = new Player( "Andrew", "S" );
        $players[] = new Player( "Casey", "P" );
        $players[] = new Player( "Cadman", "R" );

        $playerCtrl = new PlayerController( $players, $this->hands );

        $playerCtrl->validatePlayerHands( $players );

        $this->assertEquals( null, $this->getExpectedException() );
    }

    public function testSetValidPlayers()
    {
        $this->assertEquals( 6, sizeof( $this->playerCtrl->getPlayers() ) );

        $this->invokeMethod( $this->playerCtrl, 'setValidPlayers' );

        $this->assertEquals( 5, sizeof( $this->playerCtrl->getPlayers() ) );
    }

    public function testGetPlayers()
    {
        $this->assertEquals( 6, sizeof( $this->playerCtrl->getPlayers() ) );
    }
}
