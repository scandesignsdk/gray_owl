<?php

namespace SDM\RPS;

use PHPUnit\Framework\TestCase;

class MatchControllerTest extends TestCase
{

    /**
     * @var MatchController
     */
    protected $matchCtrl;

    /**
     * @var Player[]
     */
    protected $matchPlayers;

    /**
     * @var array
     */
    protected $hands;

    protected function setUp()
    {
        $this->hands = [ 'R',
                         'S',
                         'P' ];

        $this->matchPlayers[] = new Player( "Adam", "P" );
        $this->matchPlayers[] = new Player( "Andrew", "S" );

        $this->matchCtrl = new MatchController( $this->hands );
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
     * The winner of the match (with only one player).
     * (If player does not have any opponent.)
     */
    public function testGetWinnerOnePlayer()
    {
        $this->matchPlayers = null;
        $this->matchPlayers[] = new Player( "Adam", "P" );

        $this->assertEquals( $this->matchPlayers[ 0 ], $this->matchCtrl->getWinner( $this->matchPlayers ) );
    }

    /**
     * The winner of the match (with two players).
     */
    public function testGetWinnerWithToPlayer()
    {
        $this->matchPlayers = null;

        $this->matchPlayers[] = new Player( "Adam", "P" );
        $this->matchPlayers[] = new Player( "Andrew", "S" );

        $this->assertEquals( $this->matchPlayers[ 1 ], $this->matchCtrl->getWinner( $this->matchPlayers ) );
    }

    /**
     * The winner of the match.
     */
    public function testGetMatchWinner()
    {
        $this->invokeMethod( $this->matchCtrl, 'setPlayers', [ $this->matchPlayers ] );
        $method = $this->invokeMethod( $this->matchCtrl, 'getMatchWinner' );

        $this->assertEquals( $this->matchPlayers[ 1 ], $method );
    }

    /**
     * Is given players set
     */
    public function testSetPlayers()
    {
        $this->invokeMethod( $this->matchCtrl, 'setPlayers', [ $this->matchPlayers ] );
        $matchPlayers = $this->invokePrivateProperty( $this->matchCtrl, 'matchPlayers' );

        $this->assertEquals( 2, sizeof( $matchPlayers ) );
    }
}
