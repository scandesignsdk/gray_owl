<?php
namespace SDM\RPS;

class InvalidTournamentException extends \Exception
{
	public function errorMessage() {
		//error message
		$errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile().': <b>'.$this->getMessage().'</b> This is not a valid tournament.';
		return $errorMsg;
	}
}
