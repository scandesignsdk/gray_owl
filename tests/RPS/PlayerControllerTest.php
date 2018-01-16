<?php

namespace SDM\RPS;

use PHPUnit\Framework\TestCase;


class PlayerControllerTest extends TestCase
{

    /**
     * @var PlayerController
     */
    protected $playerCtrl;
    protected $players;
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
     * @covers PlayerController::getValidPlayers()
     */
    public function testGetValidPlayers()
    {
        $this->assertEquals( 5, sizeof( $this->playerCtrl->getValidPlayers() ) );
    }

    /**
     * Test not valid player hand.
     * @throws InvalidTournamentException
     * @covers PlayerController::validatePlayerHand()
     */
    public function testValidatePlayerHand()
    {
        $this->expectException( InvalidTournamentException::class );

        $this->playerCtrl->validatePlayerHand( $this->players[ 2 ] );
    }

    /**
     * Test not valid player hands.
     * @throws InvalidTournamentException
     * @covers PlayerController::validatePlayerHands()
     */
    public function testValidatePlayerHands()
    {
        $this->expectException( InvalidTournamentException::class );

        $this->playerCtrl->validatePlayerHands( $this->players );
    }


    /**
     * @covers PlayerController::setValidPlayers()
     */
    public function testSetValidPlayers()
    {
        $this->assertEquals( 6, sizeof( $this->playerCtrl->getPlayers() ) );

        $this->invokeMethod( $this->playerCtrl, 'setValidPlayers' );

        $this->assertEquals( 5, sizeof( $this->playerCtrl->getPlayers() ) );
    }

    /**
     * @covers PlayerController::getPlayers()
     */
    public function testGetPlayers()
    {
        $this->assertEquals( 6, sizeof( $this->playerCtrl->getPlayers() ) );
    }
}
