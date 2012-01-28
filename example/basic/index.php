<?php
	require_once(dirname(__FILE__).'/../server/php/OpenPipe/Runner.php');
	
	$openPipeAdapter = new OpenPipe_Adapter_Basic();
	
	$openPipeRunner = new OpenPipe_Runner($openPipeAdapter);
	$openPipeRunner->run();
	
?>