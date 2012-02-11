<?php
/**
*	A runner is the  core object for any OpenPipe based application. A runner is responsible for gathering output from an OpenPipe_Adapter_Interface based
*	adapter and returning to the end client browser as piped data objects. Before sending these piped based data objects this runner also ensures that the
*	end client browser has been setup/instantiated appropriately by sending the CORE OpenPipe front end JavaScript libraries and the CORE HTML framework
*
*	Once constructed calling this object run() method will kickoff the OpenPipe HTTP pipelining process
*
*	@author Sean Kenny
*	@package OpenPipe
*	@version <version_id>
**/


require_once('Output/Piped.php');
require_once('Adapter/Interface.php');
require_once('Pipelet/Factory.php');

class OpenPipe_Runner {
	
	/**
	*	The OpenPipe_Adapter_Interface object that is used by this OpenPipe_Runner output to gather pipelets and load individual pipelet data for
	*	@var OpenPipe_Adapter_Interface
	*	@access protected
	*/
	protected $frameworkAdapter;


	/**
	*	Constructs an OpenPipe_Runner object that communicated with the given OpenPipe_Adapter_Interface based object
	*	@param OpenPipe_Adapter_Interface $frameworkAdapter
	*	@return OpenPipe_Runner
	*/
	public function __construct(OpenPipe_Adapter_Interface $frameworkAdapter){
		$this->frameworkAdapter = $frameworkAdapter;
	}

	
	/**
	*	Is responsible for the ENTIRE OpenPipe HTTP pipelining lifecycle - handle all bootstrapping, base client library loading, output gathering, output transmission, cleanup, and shutdown
	*	@return void
	*/
	public function run(){
		$this->bootstrap();
		$this->header();
		
		$pipelets = array();
		$pipeletsQueue = array();

		$phase = 0;
		$currentPipelet = null;

		$layout = $this->frameworkAdapter->getOutput();
		$layout = str_replace("'", "\\'", $layout);
		op_piped_echo_js("op.load({'id': 'op-container', 'html': '$layout' });");
	
		$pipelets= OpenPipe_Pipelet_Factory::buildFromHtml($layout, $phase);
		
		while(!empty($pipelets)){
			$currentPipelet =  array_shift($pipelets);
			$this->frameworkAdapter->getOutput($currentPipelet);
			
			$this->pipe($currentPipelet);
			
			//$pipeletsQueue = array_merge($pipeletsQueue, OpenPipe_Pipelet_Factory::buildFromHtml($, $phase));
			
			if(empty($pipelets)){
				$pipelets = $pipeletsQueue;
				$pipeletsQueue = array();
				
				op_piped_echo_js("op.phaseComplete($phase);");
				++$phase;
				
			}
			
		}
		
		$this->footer();
		$this->cleanup();
	}
	
	
	/**
	*	Given an OpenPipe_Pipelet_Interface based object extract the relevant OpenPipe Javascript components and output as JavaScript to the end client browser
	*	@param OpenPipe_Pipelet_Interface $pipelet pipelet to extract data from and send as OpenPipe client library loadable JSON data
	*	@return void
	*/
	protected function pipe($pipelet){
		$id = $pipelet->getId();
		$css = $this->extractCssJsonArray($pipelet);
		$js = $this->extractJsJsonArray($pipelet);
		$html = $this->extractHtml($pipelet);

		op_piped_echo_js("op.load({'id': '$id', 'html': '$html', 'css': $css, 'scripts': $js});");

	}
	
	
	/**
	*	Given an OpenPipe_Pipelet_Interface object extract the css information from the raw data and encode in a JSON array
	*	@param OpenPipe_Pipelet_Interface $pipelet pipelet to extract CSS data and JSON encode as an array of CSS 
	*	@return string JSON encoded array of CSS stylesheet extracted from the $pipelet output
	*/
	protected function extractCssJsonArray($pipelet){
		preg_match_all('/<style.*?>.*?<\/style>/', $pipelet->getOutput(), $matches, PREG_SET_ORDER);
		$pipelet->setOutput(preg_replace('/<style.*?>.*<\/style>/', '', $pipelet->getOutput()));
		
		$css = array();
		foreach($matches as $match){
			$css[] = $match[0];
		}
		
		return json_encode($css);
	}
	
	/**
	*	Given an OpenPipe_Pipelet_Interface object extract the JavaScript information from the raw data and encode in a JSON array
	*	@param OpenPipe_Pipelet_Interface $pipelet pipelet to extract JavaScript data and JSON encode as an array of JavaScript data
	*	@return string JSON encoded array of JavaScript tags extracted from the $pipelet output
	*/	
	protected function extractJsJsonArray($pipelet){
		preg_match_all('/<script.*?>.*?<\/script>/', $pipelet->getOutput(), $matches, PREG_SET_ORDER);
		$pipelet->setOutput(preg_replace('/<script.*?>.*<\/script>/', '', $pipelet->getOutput()));
		
		$js = array();
		foreach($matches as $match){
			$js[] = $match[0];
		}
		
		return json_encode($js);
	}
	
	
	/**
	*	Obtain raw output from the given pipelet
	*	@param OpenPipe_Pipelet_Interface $pipelet pipelet to obtain output from
	*	@return string output from the pipelet object
	*/
	protected function extractHtml($pipelet){
		$output = $pipelet->getOutput();
		return $output;
	}
	

	/**
	*	Performs bootstrapping of OpenPipe runner object and calls the injected OpenPipe_Adapter_Interface bootstrap() method at the very end
	*	@return void
	*/
	protected function bootstrap(){
		
		//set 1024 newline - this forces soutput to the browser to start
		echo str_repeat(" ",1024);
		flush();
		
		$this->frameworkAdapter->bootstrap();
	}
	
	/**
	*   Performs cleanup of OpenPipe runner object and calls the injected OpenPipe_Adapter_Interface cleanup() method at the very end
	*	@return void
	*/
	protected function cleanup(){
		$this->frameworkAdapter->cleanup();
	}
	
	
	/**
	*	Outputs the framework for an HTTP Pipeline HTML document - this is essentially html and Javascript libraries. Note the html is unclosed (no ending body and html tags)
	*	@return void
	*/
	protected function header(){
		op_piped_echo('<!DOCTYPE html><html><head><script type="text/javascript" src="../../../client/js/libs/jquery.js" ></script><script type="text/javascript" src="../../../client/js/libs/underscore.js"></script><script type="text/javascript" src="../../../client/js/openpipe.js"></script></head><body><div id="op-container"></div>');
		
	}
	
	/**
	*	Outputs the closing framework elements for an HTTP Pipeline HTML document - sends shutdown (done) method for client library and close initially open body and html tags.
	*	@return void
	*/
	protected function footer(){
		op_piped_echo_js('op.done();');
		op_piped_echo('</body></html>');
	}
	
	
	
	

	
}