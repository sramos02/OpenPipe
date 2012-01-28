<?php



interface OpenPipe_Adapter_Interface {
	
	function getOutput(OpenPipe_Pipelet_Interface $pipelet);
	function bootstrap();
	function cleanup();
}
