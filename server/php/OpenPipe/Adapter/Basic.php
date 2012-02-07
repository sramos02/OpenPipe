<?php
<?php
/**
*	A basic adapter which provides an implementation of the abstract adapter class. It simply loads layouts and piplets from known directories given during
*	the constructor process
*
*	@author Sean Kenny
*	@package OpenPipe_Adapter
*	@version <version_id>
**/

require_once('Abstract.php');

class OpenPipe_Adapter_Basic extends OpenPipe_Adapter_Abstract {
	
    /**
     * The full path to the layouts php files that will be loaded by this adapter
     *
     * @var string
     * @access public
     */
	public $layoutsPath;
	
	/**
     * The full path to the pipelets php files that will be loaded by this adapter
     *
     * @var string
     * @access public
     */
	public $pipeletsPath;
	
	/**
	*	Constructs a new Basic pipe adapter
	*	@param string $layoutsPath the full path to the layouts php file that will be loaded by this adapter
	*	@param string $pipeletsPath the full path to the pipelets php files that will be loaded by this adapter
	*	@return OpenPipe_Basic_Adapter new instance of this object
	*/
	public function __construct($layoutsPath, $pipeletsPath){
		$this->layoutsPath = $layoutsPath;
		$this->pipeletsPath = $pipeletsPath; 
	}
	
	/**
	*	loads a php layout via include()
	*	
	*	@param string $id the id of the layout to be used. An id is the filename without the php extension. For example default.php would be default
	*	@return void
	*/
 	protected function getLayout($id='default'){
		include($this->layoutsPath.'/'.$id.'.php');
	}

	/**
	*	loads a php pipelet via include()
	*	
	*	@param string $id the id of the pipelet to be used. An id is the filename without the php extension. For example default.php would be default
	*	@return void
	*/	
	protected function getContent(OpenPipe_Pipelet_Interface $pipelet){
		include($this->pipeletsPath.'/'.$pipelet->getId().'.php');
	}
	
}
