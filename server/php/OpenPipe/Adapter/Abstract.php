<?php



abstract class OpenPipe_Adapter_Basic implements OpenPipe_Adapter_Interface{
	
	public function getOutput(OpenPipe_Pipelet_Interface $pipelet=null){
		if($pipelet === null){
			$this->getLayout();
		}else{
			$thit->getContent($pipelet);
		}
	}
	
	
	
	abstract protected function getLayout();
	abstract protected function getContent(OpenPipe_Pipelet_Interface $pipelet);
	
	
	public function bootstrap(){ }
	public function cleanup(){ }
		
}

