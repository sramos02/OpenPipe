<?php
/**
*	Generated pipelets using factory based methods
*
*	@author Sean Kenny
*	@package OpenPipe_Pipelet
*	@version <version_id>
**/

require_once('Base.php');

class OpenPipe_Pipelet_Factory{
	
	
	/**
	*	Extracts an array of pipelets from an given HTML document (represented as s string)
	*	
	*	@param string $html An html document represented via string
	*	@param int|string $phase The current phase of the pipelet loading process. This is assigned to any loaded pipelets extracted from the first html string parameter
	*	@return array<OpenPipe_Pipelet_Base> an array of all pipelets extracted from the HTML input and instantiated as OpenPipe_Pipelet_Base objects
	*/
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