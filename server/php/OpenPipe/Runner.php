<?php
/**
*	A runner is the  core object for any OpenPipe based application. A runner is responsible for gathering output from an OpenPipe_Adapter_Interface based
*	adapter and returning to the end client browser as piped data objects. Before sending these piped based data objects this runner also ensures that the
*	end client browser has been setup/instantiated appropriately by sending the CORE OpenPipe front end JavaScript libraries and the CORE HTML framework
*
*	Once constructed calling this object run() method will kickoff the OpenPipe HTTP pipelining process
*
*	@author Sean Kenny @author Sean Kenny <skenny214@gmail.com>|<kennys1@southernct.edu>
*	@package OpenPipe
*	@license (c) 2011-2012 Sean Kenny, Southern Connecticut State University (SCSU).
*	@version <version_id>
**/


require_once('Output/Util.php');
require_once('Adapter/Interface.php');
require_once('Pipelet/Factory.php');

class OpenPipe_Runner {
	
	/**
	*	The OpenPipe_Adapter_Interface object that is used by this OpenPipe_Runner to gather pipelets and load individual pipelet data
	*	@var OpenPipe_Adapter_Interface
	*	@access protected
	*/
	protected $frameworkAdapter;
	
	/**
	*	The OpenPipe_Output_Interface object that is used by this OpenPipe_Runner to send output data to the browser
	*	@var OpenPipe_Output_Interface
	*	@access protected
	*/	
	protected $output;



	/**
	*	Constructs an OpenPipe_Runner object that communicated with the given OpenPipe_Adapter_Interface based object
	*	@param OpenPipe_Adapter_Interface $frameworkAdapter
	*	@return OpenPipe_Runner
	*/
	public function __construct(OpenPipe_Adapter_Interface $frameworkAdapter, OpenPipe_Output_Interface $output){
		$this->frameworkAdapter = $frameworkAdapter;
		$this->output = $output;
	}

	
	/**
	*	Is responsible for the ENTIRE OpenPipe HTTP pipelining lifecycle - handle all bootstrapping, base client library loading, output gathering, output transmission, cleanUp, and shutdown
	*	@return void
	*/
	public function run(){
		$this->bootstrap();
		$this->output->preContent();

		$layout = $this->frameworkAdapter->getOutput();
		$this->output->content($layout);
	
	
		$pipelets= OpenPipe_Pipelet_Factory::buildFromHtml($layout, $phase);
		$pipeletsQueue = array();
		
		
		$phase = 0;
		$this->output->phaseStart($phase);
		
		while(!empty($pipelets)){
			
			$currentPipelet =  array_shift($pipelets);
			
			$this->frameworkAdapter->getOutput($currentPipelet);
			$this->output->content($currentPipelet);
			
			$pipeletsQueue = array_merge($pipeletsQueue, OpenPipe_Pipelet_Factory::buildFromHtml($currentPipelet->getOutput(), $phase+1));
			
			if(empty($pipelets)){
				$pipelets = $pipeletsQueue;
				$pipeletsQueue = array();

				$this->output->phaseEnd(++$phase);
				$this->output->phaseStart($phase);
		
			}
			
		}
		
		$this->footer();
		$this->cleanUp();
	}

	
	

	/**
	*	Performs bootstrapping of OpenPipe runner object and calls the injected OpenPipe_Adapter_Interface bootstrap() method at the very end
	*	@return void
	*/
	protected function bootstrap(){
		$this->frameworkAdapter->bootstrap();
		$this->output->bootstrap();
	}
	
	/**
	*   Performs cleanUp of OpenPipe runner object and calls the injected OpenPipe_Adapter_Interface cleanUp() method at the very end
	*	@return void
	*/
	protected function cleanUp(){
		$this->frameworkAdapter->cleanUp();
		$this->output->cleanUp();
	}

	
	
}