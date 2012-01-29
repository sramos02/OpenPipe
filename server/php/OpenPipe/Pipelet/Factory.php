<?php

require_once('Base.php');

class OpenPipe_Pipelet_Factory{
	
	
	public static function buildFromHtml($html, $phase){
		
		
		$pipelets = array();
		preg_match_all('/pipelet\-id\=[\'\"](.*?)[\'\"]/', $html, $matches, PREG_SET_ORDER);

		foreach($matches as $match){
			$pipelets[] = new OpenPipe_Pipelet_Base($match[1], $phase);
		}

		
		return $pipelets;
	}
}