<?php
/**
*	A PMVC adapter which provides an implementation of for CodeIgniter 2.x applications to take advantage of piped output
*
*	@author Sean Kenny
*	@package OpenPipe_Adapter
*	@version <version_id>
**/

require_once(dirname(__FILE__).'/../Abstract.php');

$BM;
$CFG;



class OpenPipe_Adapter_Pvc_CodeIgniter extends OpenPipe_Adapter_Abstract {
	
	/**
	*	The root path of the currently active CodeIgniter application
	*	@var string
	*	@access protected
	*/
	protected $appRootPath;
	
	
	/**
	*	The file name within the $appRootPath that bootstraps and runs a CodeIgniter application
	*	@var string 
	*	@access protected
	*/
	protected $indexFileName;
	
	/**
	*	Constructs a new CodeIgniter pipe adapter
	*	@param string $appRootPath the root path of the currently active CodeIgniter application
	*	@param string $indexFileName the file name within the $appRootPath that bootstraps and runs a CodeIgniter application
	*	@return OpenPipe_Adapter_Pvc_CodeIgniter new instance of this object
	*/
	public function __construct($appRootPath, $indexFileName='index.pipe.php'){
		$this->appRootPath = rtrim($appRootPath, '/');
		$this->indexFileName = $indexFileName;
	}
	
	/**
	*	loads a php layout by starting the code igniter index file
	*	
	*	@param string $indexFileName the index file name which bootstraps and loads the CodeIgniter applications
	*	@return void
	*/
 	protected function getLayout(){
		global $BM, $CFG;
		
		include($this->appRootPath.'/'.$this->indexFileName);
		echo 'test';
	}

	/**
	*	loads a php pipelet via include()
	*	
	*	@param string $id the id of the pipelet to be used. An id is the filename without the php extension. For example default.php would be default
	*	@return void
	*/	
	protected function getContent(OpenPipe_Pipelet_Interface $pipelet){
		die('wtf');
	}
	
}
