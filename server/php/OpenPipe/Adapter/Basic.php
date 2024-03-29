<?php

require_once('Abstract.php');

/**
* A basic adapter which provides an implementation of the abstract adapter class. It simply loads layouts and piplets from known directories given during
* the constructor process
* @author Sean Kenny <skenny214@gmail.com>
* @package OpenPipe_Adapter
* @license (c) 2011-2012 Sean Kenny, Southern Connecticut State University (SCSU).
* @version 1.0.0
*/
class OpenPipe_Adapter_Basic extends OpenPipe_Adapter_Abstract {
	
    /**
     * The full path to the layouts php files that will be loaded by this adapter
     * @var string
     */
	public $layoutsPath;
	
	/**
     * The full path to the pipelets php files that will be loaded by this adapter
     * @var string
     */
	public $pipeletsPath;
	
	/**
	* Constructs a new Basic pipe adapter
	* @param string $layoutsPath the full path to the layouts php file that will be loaded by this adapter
	* @param string $pipeletsPath the full path to the pipelet php files that will be loaded by this adapter
	*/
	public function __construct($layoutsPath, $pipeletsPath){
		$this->layoutsPath = $layoutsPath;
		$this->pipeletsPath = $pipeletsPath; 
	}
	
	/**
	* loads a php layout via include()
	* @param string $id the id of the layout to be used - An id is the filename without the php extension - For example default.php would be default
	* @return void
	*/
 	protected function getLayout($id='default'){
		include($this->layoutsPath.'/'.$id.'.php');
	}

	/**
	* loads a php pipelet via include()
	* @param OpenPipe_Pipelet_Interface $pipelet the pipelet to load content for
	* @return void
	*/	
	protected function getContent(OpenPipe_Pipelet_Interface $pipelet){
		include($this->pipeletsPath.'/'.$pipelet->getId().'.php');
	}
	
}
