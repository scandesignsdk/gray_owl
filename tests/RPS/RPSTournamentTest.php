<?php

namespace SDM\RPS;

use PHPUnit\Framework\TestCase;

class RPSTournamentTest extends TestCase
{
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
     * Get private property
     *
     * @param $object
     * @param $propertyName
     *
     * @return mixed
     */
    public function invokePrivateProperty( &$object, $propertyName )
    {
        $reflection = new \ReflectionClass( get_class( $object ) );
        $property = $reflection->getProperty( $propertyName );
        $property->setAccessible( true );

        return $property->getValue( $object );
    }

    /**
     * @throws InvalidTournamentException
     * @throws CancelledTournamentException
     */
    public function testGetWinner_one_non_valid_hand()
    {
        $players[] = new Player( "Adam", "P" );
        $players[] = new Player( "Andrew", "S" );
        $players[] = new Player( "Chris", "r" ); // non valid hand
        $players[] = new Player( "Casey", "P" );
        $players[] = new Player( "Cadman", "R" );

        $rpsTournament = new RPSTournament( $players );

        $this->expectException( InvalidTournamentException::class );

        $this->assertEquals( $players[ 4 ], $rpsTournament->getWinner() );
    }

    /**
     * @throws InvalidTournamentException
     * @throws CancelledTournamentException
     */
    public function testGetWinner_not_enough_players()
    {
        $players[] = new Player( "Adam", "P" );
        $rpsTournament = new RPSTournament( $players );

        $this->expectException( CancelledTournamentException::class );

        $this->assertEquals( $players[ 0 ], $rpsTournament->getWinner() );
    }

    public function testIsValidTournament_one_players()
    {
        $players[] = new Player( "Adam", "P" );

        $rpsTournament = new RPSTournament( $players );

        $this->expectException( CancelledTournamentException::class );

        $method = $this->invokeMethod( $rpsTournament, 'isValidTournament' );

        $this->assertTrue( $method );
    }

    public function testIsValidTournament_multiple_players()
    {
        $players[] = new Player( "Adam", "P" );
        $players[] = new Player( "Andrew", "S" );
        $players[] = new Player( "Casey", "P" );
        $players[] = new Player( "Cadman", "R" );

        $rpsTournament = new RPSTournament( $players );

        $method = $this->invokeMethod( $rpsTournament, 'isValidTournament' );

        $this->assertTrue( $method );
    }

    public function testUnsetMatchPlayers_multiple_players()
    {
        $players[] = new Player( "Adam", "P" );
        $players[] = new Player( "Andrew", "S" );
        $players[] = new Player( "Casey", "P" );
        $players[] = new Player( "Cadman", "R" );

        $rpsTournament = new RPSTournament( $players );

        $this->invokeMethod( $rpsTournament, 'setValidPlayers' );
        $this->invokeMethod( $rpsTournament, 'unsetMatchPlayers' );
        $property = $this->invokePrivateProperty( $rpsTournament, 'players' );

        $this->assertEquals( 2, sizeof( $property ) );
    }

    public function testUnsetMatchPlayers_one_players()
    {
        $players[] = new Player( "Adam", "P" );

        $rpsTournament = new RPSTournament( $players );

        $this->invokeMethod( $rpsTournament, 'unsetMatchPlayers' );
        $property = $this->invokePrivateProperty( $rpsTournament, 'players' );

        $this->assertEquals( 0, sizeof( $property ) );
    }

    public function testGetMatchPlayers_multiple_players()
    {
        $players[] = new Player( "Adam", "P" );
        $players[] = new Player( "Andrew", "S" );
        $players[] = new Player( "Casey", "P" );
        $players[] = new Player( "Cadman", "R" );

        $rpsTournament = new RPSTournament( $players );

        $this->invokeMethod( $rpsTournament, 'setValidPlayers' );
        $method = $this->invokeMethod( $rpsTournament, 'getMatchPlayers' );

        $this->assertEquals( 2, sizeof( $method ) );
    }

    public function testGetMatchPlayers_one_player()
    {
        $players[] = new Player( "Adam", "P" );

        $rpsTournament = new RPSTournament( $players );

        $method = $this->invokeMethod( $rpsTournament, 'getMatchPlayers' );

        $this->assertEquals( 1, sizeof( $method ) );
    }

    public function testRunMatches()
    {
        $players[] = new Player( "Adam", "P" );
        $players[] = new Player( "Andrew", "S" );
        $players[] = new Player( "Casey", "P" );
        $players[] = new Player( "Cadman", "R" );

        $rpsTournament = new RPSTournament( $players );

        $this->invokeMethod( $rpsTournament, 'setValidPlayers' );
        $method = $this->invokeMethod( $rpsTournament, 'runMatches' );

        $this->assertEquals( $players[ 1 ], $method );
    }
}
