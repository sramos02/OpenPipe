<?php



require_once('Output/Piped.php');
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
	
	
	protected function pipe($pipelet){
		$id = $pipelet->getId();
		$css = $this->extractCssJsonArray($pipelet);
		$js = $this->extractJsJsonArray($pipelet);
		$html = $this->extractHtml($pipelet);

		op_piped_echo_js("op.load({'id': '$id', 'html': '$html', 'css': $css, 'scripts': $js});");

	}
	
	
	protected function extractCssJsonArray($pipelet){
		preg_match_all('/<style.*?>.*?<\/style>/', $pipelet->getOutput(), $matches, PREG_SET_ORDER);
		$pipelet->setOutput(preg_replace('/<style.*?>.*<\/style>/', '', $pipelet->getOutput()));
		
		$css = array();
		foreach($matches as $match){
			$css[] = $match[0];
		}
		
		return json_encode($css);
	}
	
	
	protected function extractJsJsonArray($pipelet){
		preg_match_all('/<script.*?>.*?<\/script>/', $pipelet->getOutput(), $matches, PREG_SET_ORDER);
		$pipelet->setOutput(preg_replace('/<script.*?>.*<\/script>/', '', $pipelet->getOutput()));
		
		$js = array();
		foreach($matches as $match){
			$js[] = $match[0];
		}
		
		return json_encode($js);
	}
	
	protected function extractHtml($pipelet){
		$output = $pipelet->getOutput();
		return $output;
	}
	

	protected function bootstrap(){
		
		//set 1024 newline - this forces soutput to the browser to start
		echo str_repeat(" ",1024);
		flush();
		
		$this->frameworkAdapter->bootstrap();
	}
	
	
	protected function cleanup(){
		$this->frameworkAdapter->cleanup();
	}
	
	
	protected function header(){
		op_piped_echo('<!DOCTYPE html><html><head><script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" ></script><script type="text/javascript" src="../../client/js/libs/underscore.js"></script><script type="text/javascript" src="../../client/js/openpipe.js"></script></head><body><div id="op-container"></div>');
		
	}
	
	protected function footer(){
		op_piped_echo_js('op.done();');
		op_piped_echo('</body></html>');
	}
	
	
	
	

	
}