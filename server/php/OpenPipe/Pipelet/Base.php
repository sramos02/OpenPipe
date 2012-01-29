<?php

require_once('Abstract.php');

class OpenPipe_Pipelet_Base extends OpenPipe_Pipelet_Abstract{
	
	public function __construct($id, $phase){
		$this->setId($id);
		$this->setPhase($phase);
	}
}