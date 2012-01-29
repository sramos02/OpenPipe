<?php

require_once('Adapter/Interface.php');
require_once('Pipelet/Factory.php');

class OpenPipe_Runner {
	
	public $frameworkAdapter;	
	public $browserInfo;

	public function __construct(OpenPipe_Adapter_Interface $frameworkAdapter){
		$this->frameworkAdapter = $frameworkAdapter;
	}

	
	public function run(){
		$this->bootstrap();
		$this->header();
		
		$pipelets = array();
		$pipeletsQueue = array();

		$phase = 0;
		$currentPipelet = null;

		$layout = $this->frameworkAdapter->getOutput();
		$layout = str_replace("\n", '', $layout);
		echo "<script type='text/javascript' >op.load({'id': 'op-container', 'html': '$layout' });</script>";
		flush();
	
		$pipelets= OpenPipe_Pipelet_Factory::buildFromHtml($layout, $phase);
		
		while(!empty($pipelets)){
			$currentPipelet =  array_shift($pipelets);
			$this->frameworkAdapter->getOutput($currentPipelet);
			
			$this->pipe($currentPipelet);
			
			//$pipeletsQueue = array_merge($pipeletsQueue, OpenPipe_Pipelet_Factory::buildFromHtml($, $phase));
			
			if(empty($pipelets)){
				$pipelets = $pipeletsQueue;
				$pipeletsQueue = array();
				
				echo "<script type='text/javascript' >op.phaseComplete($phase)</script>";
				flush();
				++$phase;
				
			}
			
		}
		
		$this->footer();
		$this->cleanup();
	}
	
	
	protected function pipe($pipelet){
		$id = $pipelet->getId();
		$css = $this->extractCssJsonArray($pipelet);
		$js = $this->extractJsJsonArray($pipelet);
		$html = $this->extractHtml($pipelet);

		echo "<script type='text/javascript' >op.load({'id': '$id', 'html': '$html', 'css': $css, 'script': $js});</script>";
		flush();
	}
	
	
	protected function extractCssJsonArray(){
		return json_encode(array());
	}
	
	protected function extractJsJsonArray(){
		return json_encode(array());
	}
	
	protected function extractHtml($pipelet){
		$output = str_replace("\n", '', $pipelet->getOutput());
		
		return $output;
	}
	

	protected function bootstrap(){
		
		//set 1024 newline - this forces soutput to the browser to start
		echo str_repeat(" ",1024);
		flush();
		
		$this->frameworkAdapter->bootstrap();
	}
	
	
	protected function cleanup(){
		ini_set('implicit_flush', Off);
		$this->frameworkAdapter->cleanup();
	}
	
	
	protected function header(){
		echo '<!DOCTYPE html><html><head><script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" ></script><script type="text/javascript" src="../../client/js/libs/underscore.js"></script><script type="text/javascript" src="../../client/js/openpipe.js"></script></head><body><div id="op-container"></div>';
		flush();
	}
	
	protected function footer(){
		echo '<script type="text/javascript" >op.done()</script></body></html>';
		flush();
	}
	
	
	
	

	
}