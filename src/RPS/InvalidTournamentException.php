<?php
namespace SDM\RPS;

class InvalidTournamentException extends \Exception
{
    public function __construct( $message = null, $code = 0, Exception $previous = null )
    {
        $message = is_null( $message ) ? $this->errorMessage() : $message;
        parent::__construct( $message, $code, $previous );
    }

    private function errorMessage()
    {
        return "Tournament invalid! The tournament only accept three types of hands, (Rock, Scissor and Paper).";
    }
}
