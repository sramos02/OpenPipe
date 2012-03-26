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
*	@version 1.0.0
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
	*	Is responsible for the ENTIRE OpenPipe HTTP pipelining lifecycle - handle all bootstrapping, base client library loading, output gathering, output transmission, Script, and shutdown
	*	@return void
	*/
	public function run(){
		$this->bootstrap();
		$this->output->preContent();

		//ask the framework for the root output layer (the layout!). This contains the starting point for all pipelets to get recognized and loaded from
		$layout = $this->frameworkAdapter->getOutput();
		$this->output->content($layout);
	
		$phase = 0;
		$pipelets= OpenPipe_Pipelet_Factory::buildFromHtml($layout, $phase);
		$pipeletsQueue = array();
		
		while(!empty($pipelets)){
			
			$currentPipelet =  array_shift($pipelets);
			
			$this->frameworkAdapter->getOutput($currentPipelet);
			$this->output->content($currentPipelet);
			
			
			//add piplets contained within the current pipelet to the the pipelet queue - the pipelet queue will get loaded as part of the next phase
			$pipeletsQueue = array_merge($pipeletsQueue, OpenPipe_Pipelet_Factory::buildFromHtml($currentPipelet->getOutput(), $phase+1));
			
			//once the current pipelets have been completed. Check the queue. If the queue is not empty then move batch to the pipelets array for processing, and mark the current pahse complete
			if(empty($pipelets)){
				$pipelets = $pipeletsQueue;
				$pipeletsQueue = array();

				$this->output->phaseComplete(++$phase);
		
			}
			
		}
		
		$this->clean();
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
	*   Performs Script of OpenPipe runner object and calls the injected OpenPipe_Adapter_Interface clean() method at the very end
	*	@return void
	*/
	protected function clean(){
		$this->frameworkAdapter->clean();
		$this->output->clean();
	}

	
	
}