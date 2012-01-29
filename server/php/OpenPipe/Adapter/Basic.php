<?php

require_once('Abstract.php');

class OpenPipe_Adapter_Basic extends OpenPipe_Adapter_Abstract {
	
	public $layoutsPath;
	public $pipeletsPath;
	
	public function __construct($layoutsPath, $pipeletsPath){
		$this->layoutsPath = $layoutsPath;
		$this->pipeletsPath = $pipeletsPath; 
	}
	
 	protected function getLayout($id='default'){
		include_once($this->layoutsPath.'/'.$id.'.php');
	}
	
	protected function getContent(OpenPipe_Pipelet_Interface $pipelet){
		include_once($this->pipeletsPath.'/'.$pipelet->getId().'.php');
	}
	
}
