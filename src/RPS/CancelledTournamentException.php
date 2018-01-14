<?php

namespace SDM\RPS;

use Exception;

class CancelledTournamentException extends Exception
{
    public function __construct( $message = null, $code = 0, Exception $previous = null )
    {
        $message = is_null( $message ) ? $this->errorMessage() : $message;
        parent::__construct( $message, $code, $previous );
    }

    private function errorMessage()
    {
        return "Tournament cancelled! To start a new tournament you need more then 1 player.";
    }
}
