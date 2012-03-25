<?php
/**
*	Represents an abstract OpenPipe adapter. As an abstract class it provides basic services for obtaining the layout (root object for pipelets), and generating
*	output for pipelets that are requested. Any object which extends this class will implement the getLayout() and getContent() methods. This abtract class will
*	handle the details in regards to buffering output and sending it back to the requesting object.
*
*	@author Sean Kenny @author Sean Kenny <skenny214@gmail.com>|<kennys1@southernct.edu>
*	@package OpenPipe_Adapter
*	@license (c) 2011-2012 Sean Kenny, Southern Connecticut State University (SCSU).
*	@version <version_id>
**/

require_once('Interface.php');

abstract class OpenPipe_Adapter_Abstract implements OpenPipe_Adapter_Interface{
	
	/**
	*	Returns output for the given pipelet. Output is web content (html, css, javascript). If Pipelet is null then the layout is generated.
	*
	*	@param OpenPipe_Pipelet_Interface $pipelet if not specified then the adapter will generate the Pipelets layout by default
	*	@return string|OpenPipe_Pipelet_Interface given string output either generated for layout or a OpenPipe_Pipelet_Interface with output set
	*/
	public function getOutput(OpenPipe_Pipelet_Interface $pipelet=null){
		
		ob_start();
		
		if($pipelet === null){
			$this->getLayout();
		}else{
			$this->getContent($pipelet);
			
		}
		
		$output = ob_get_contents();
		ob_end_clean();
		
		
		if($pipelet !== null){
			$pipelet->setOutput($output);
			return $pipelet;
		}else{
			return $output;
		}
		
	}
	
	
	/**
	*	Method should return the layout for the given web request
	*
	*	@return string the root layout for all pipelets to be derived from
	*/
	abstract protected function getLayout();
	
	/**
	*	Method should return the layout for the given web request
	*
	*	@return string the root layout for all pipelets to be derived from
	*/
	abstract protected function getContent(OpenPipe_Pipelet_Interface $pipelet);
	
	
	/**
	*	This abstract class does not provided any bootstrapping logic
	*/
	public function bootstrap(){ }
	
	/**
	*	This abstract class does not provided any Script logic
	*/
	public function clean(){ }
		
}

