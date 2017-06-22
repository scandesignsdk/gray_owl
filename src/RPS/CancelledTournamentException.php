<?php
namespace SDM\RPS;

class CancelledTournamentException extends \Exception
{
	public function errorMessage() {
		//error message
		$errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile().': <b>'.$this->getMessage().'</b> This tournament is cancelled.';
		return $errorMsg;
	}
}
