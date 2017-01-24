<?php
namespace SDM\RPS;

class Player
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $hand;

    /**
     * @param string $name
     * @param string $hand
     */
    public function __construct($name, $hand)
    {
        $this->name = $name;
        $this->hand = $hand;
    }

    /**
     * @return string
     */
    public function getHand(): string
    {
        return $this->hand;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

}
