<?php
namespace SDM\RPS;

class Player
{

    /**
	 * Holds the name of the player
	 *
     * @var string
     */
    private $name;

    /**
	 * Holds the hand of the player
	 *
     * @var string
     */
    private $hand;

    /**
	 * Constructor
	 *
	 * @throws InvalidArgumentException if the name of the player is empty
	 * @throws InvalidArgumentException if the hand of the plater is empty
     * @param string $name the name of the player
     * @param string $hand the hand of the player
	 * @return void
     */
    public function __construct($name, $hand)
    {
		$name = trim($name);
		$hand = trim($hand);

		if (empty($name)) {
			throw new \InvalidArgumentException('All players must have names', 10001);
		}

		// The Tournament will take care for the invalid types of hands
		if (empty($hand)) {
			throw new \InvalidArgumentException('All players must have hands', 10002);
		}

        $this->name = $name;
        $this->hand = strtoupper($hand);
    }

    /**
	 * Gets the hand of the player
	 *
     * @return string
     */
    public function getHand(): string
    {
        return $this->hand;
    }

    /**
	 * Gets the name of the player
	 *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
