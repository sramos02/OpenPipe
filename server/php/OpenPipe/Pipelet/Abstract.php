<?php

require_once('Interface.php');

abstract class OpenPipe_Pipelet_Abstract implements OpenPipe_Pipelet_Interface {
	
	protected $id;
	protected $phase;
	
	protected $output;
	
	public function setId($id){
		$this->id = $id;
	}
	public function getId(){
		return $this->id;
	}
	
	public function setPhase($phase){
		$this->phase = $phase;
	}
	public function getPhase(){
		return $this->phase;
	}
	
	public function setOutput($output){
		$this->output = $output;
	}
	
	public function getOutput(){
		return $this->output;
	}
	
	
}