<?php



class OpenPipe_Core {
	
	public $frameworkAdapter;	
	public $browserInfo;

	public function __construct($frameworkAdapter){
		$this->frameworkAdapter = $frameworkAdapter;
	}

	
	public function run(){
		$this->bootstrap();
		$this->header();
		
		$pipelets = array();
		$pipeletsQueue = array();
		
		$css = array();
		$js = array();
		
		$phase = 0;
		$currentPipelet;
		
		while($html = $this->frameworkAdapter->getOutput($currentPipelet)){

			
			$this->pipe($currentPipelet->id, $html, $phase);
			
			$pipeletsQueue = array_merge($pipeletsQueue, $this->extractPipelets($html, $phase));
			
			if(empty($pipelets)){
				$pipelets = $pipeletsQueue;
				$pipeletsQueue = array();
				
				echo "<script type='text/javascript' >op.phaseComplete($currentPipeletPhase)</script>";
				++$phase;
				
			}else{
				$currentPipelet = array_unshift($pipelets);
			}
			
		}
		
		$this->footer();
		$this->cleanup();
	}
	
	
	protected function pipe($id, $html, $phase = 0){
		$css = $this->extractCss($html);
		$js = $this->extractJs($html);
		
		echo "<script type='text/javascript' >op.load({'id': '$id', 'html': '$html', 'css': '$css', 'script': '$js'}</script>";
	}

	protected function bootstrap(){
		$this->browserInfo = get_browser();
		$this->frameworkAdapter->bootstrap();
	}
	
	
	protected function cleanup(){
		$this->frameworkAdapter->cleanup();
	}
	
	
	protected function header(){
		echo '<!DOCTYPE html><html><head><script type="text/javascript" src="js/openpipe.js"></script></head><body>';
	}
	
	protected function footer(){
		echo '<script type="text/javascript" >op.done()</script></body></html>';
	}
}