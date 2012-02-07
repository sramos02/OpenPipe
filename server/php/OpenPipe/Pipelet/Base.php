<?php
/**
*	Provided a basic extension off of the OpenPipe_Pipelet_Abstract convenience implementation
*
*	@author Sean Kenny
*	@package OpenPipe_Pipelet
*	@version <version_id>
**/

require_once('Abstract.php');

class OpenPipe_Pipelet_Base extends OpenPipe_Pipelet_Abstract{
	
	
	/**
	* 	Builds the object 
	*	@param string $id the identifier for the pipelet 
	*	@param int|string $phase the phase for the pipelet
	*	@return OpenPipe_Pipelet_Base new instance
	*/
	public function __construct($id, $phase){
		$this->setId($id);
		$this->setPhase($phase);
	}
}