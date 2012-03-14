<?php


//set codeigniter global variables that are MUST haves

require_once(dirname(__FILE__).'/../../../server/php/OpenPipe/Adapter/Pvc/CodeIgniter.php');
require_once(dirname(__FILE__).'/../../../server/php/OpenPipe/Runner.php');

$openPipeAdapter = new OpenPipe_Adapter_Pvc_CodeIgniter(dirname(__FILE__));
$openPipeRunner = new OpenPipe_Runner($openPipeAdapter, '../../../../client/js');

$openPipeRunner->run();

?>