<?php
/**
 * Created by PhpStorm.
 * User: ismailcam
 * Date: 17/01/2018
 * Time: 03.11
 */

namespace SDM\RPS;

use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase
{
    /**
     * @var Player
     */
    protected $player;

    protected function setUp()
    {
        $this->player = new Player( "Ismail", "R" );
    }

    public function testGetName()
    {
        $this->assertEquals( "Ismail", $this->player->getName() );
    }

    public function testGetHand()
    {
        $this->assertEquals( "R", $this->player->getHand() );
    }

    public function test__toString()
    {
        $this->assertEquals( "Name: Ismail, Hand: R", $this->player->__toString() );
    }
}
