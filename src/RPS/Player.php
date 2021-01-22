<?php

namespace SDM\RPS;

class Player
{
    private string $name;

    private string $hand;

    public function __construct(string $name, string $hand)
    {
        $this->name = $name;
        $this->hand = $hand;
    }

    public function getHand(): string
    {
        return $this->hand;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
