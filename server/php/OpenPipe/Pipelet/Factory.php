<?php

require_once('Base.php');

class OpenPipe_Pipelet_Factory{
	
	
	public static function buildFromHtml($html, $phase){
		
		
		$pipelets = array();
		preg_match_all('/<.*?pipelet-id\=(\'.*?\'|".*?").*?>/', $html, $matches, PREG_SET_ORDER);

		foreach($matches as $match){
			if(!empty($match[1])){
				$pipeletId = trim($match[1], '\'" ');
				$pipelets[] = new OpenPipe_Pipelet_Base($pipeletId, $phase);
				
				sort($pipelets);
			}
		}

		
		return $pipelets;
	}
}