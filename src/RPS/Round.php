<?php

namespace SDM\RPS;

class Round {

    /**
     *
     * @var Match[]
     */
    private $matches;

    function __construct() {
        
    }

    public function getMatches(): array {
        return $this->matches;
    }

    public function setMatches(array $matches) {
        $this->matches = $matches;
    }

    public function addMatch(Match $match) {
        $this->matches[] = $match;
    }

}
