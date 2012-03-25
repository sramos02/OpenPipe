<?php

    
	require_once(dirname(__FILE__).'/../../server/php/OpenPipe/Adapter/Basic.php');
	require_once(dirname(__FILE__).'/../../server/php/OpenPipe/Output/Piped.php');
	require_once(dirname(__FILE__).'/../../server/php/OpenPipe/Runner.php');
	
	$openPipeAdapter = new OpenPipe_Adapter_Basic(dirname(__FILE__).'/layouts', dirname(__FILE__).'/pipelets');
	$openPipeOutput = new OpenPipe_Output_Piped('../../client/js');
	
	$openPipeRunner = new OpenPipe_Runner($openPipeAdapter, $openPipeOutput);
	
	$openPipeRunner->run();
