<?php

require_once('Interface.php');

abstract class OpenPipe_Adapter_Abstract implements OpenPipe_Adapter_Interface{
	
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
	
	
	
	abstract protected function getLayout();
	abstract protected function getContent(OpenPipe_Pipelet_Interface $pipelet);
	
	
	public function bootstrap(){ }
	public function cleanup(){ }
		
}

