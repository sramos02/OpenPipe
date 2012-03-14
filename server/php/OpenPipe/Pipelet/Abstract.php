<?php
/**
*	Abstract implementation of the OpenPipe_Pipelet_Interface. Provided basic bindings for all methods defined in the interface.
*
*	@author Sean Kenny
*	@package OpenPipe_Pipelet
*	@version <version_id>
**/

require_once('Interface.php');

abstract class OpenPipe_Pipelet_Abstract implements OpenPipe_Pipelet_Interface {
	
	/**
     * The unique identifier for this pipelet. that distinguishes it from all others.
     *
     * @var string
     * @access protected
     */
	protected $id;
	
	/**
     * The numbered phase of the pipelet to signify priority low-to-high
     *
     * @var string
     * @access protected
     */
	protected $phase;
	
	
	/**
     * The output that is potentially gathered and piped as output for this pipelet
     *
     * @var string
     * @access protected
     */
	protected $output;
	
	
	/**
	*	Sets the id of the pipelet (used to determine what content to gather from a Pipe Adapter)
	*	@param string $id a unique identifier for the pipelet that will signify importance to the client adapter and allow data to be looked up/generated accordingly
	*/
	public function setId($id){
		$this->id = $id;
	}
	
	/**
	*	Returns the current set pipelet id
	*/
	public function getId(){
		return $this->id;
	}
	
	
	/**
	*	Sets the phase of the pipelet (used to determine loading priorities and sequences)
	*	
	*	@param	int|string phase to set. Lower numbers are higher priority (1), than higher numbers (999)
	*/
	public function setPhase($phase){
		$this->phase = $phase;
	}
	
	/**
	*	Return the current set phase number
	*/
	public function getPhase(){
		return $this->phase;
	}
	

	/**
	*  Set the output that has been gathered for this pipelet from a Pipelet_Adapter
	*
	*  param string $output the output string that has been generated/gathered for this given pipelet
	*/
	public function setOutput($output){
		$this->output = $output;
	}

	/**
	*	Return the output that is currently set for the pipelet
	*/	
	public function getOutput(){
		return $this->output;
	}
	
	
}