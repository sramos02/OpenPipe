<?php
/**
*	Generated pipelets using factory based methods
*
*	@author Sean Kenny
*	@package OpenPipe_Pipelet
*	@license (c) 2011-2012 Sean Kenny, Southern Connecticut State University (SCSU).
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
		
		//pipelet containers
		$pipelets = array();
		$pipeletGroups = array();
		
		//setup regex to find pipelets - all pipelets need to specify at least a pipelet-id attribute
		preg_match_all('/<.*?pipelet-id.*?>/', $html, $matches, PREG_SET_ORDER);

		//for all matches extract the pipelet to its appropriate group
		foreach($matches as $match){
			//reset match arrays
			$pipeletIdMatch = array();
			$pipeletPriorityMatch = array();
			
			//extract pipelet attributes into corresponding match arrays
			preg_match('/pipelet-id\=(\'.*?\'|".*?")/', $match[0], $pipeletIdMatch);
			preg_match('/pipelet-priority\=(\'.*?\'|".*?")/', $match[0], $pipeletPriorityMatch);
			
			//assign matches to local variables
			$pipeletId = trim(@$pipeletIdMatch[1], '\'"');
			$pipeletPriority = trim(@$pipeletPriorityMatch[1], '\'"');;
			if(empty($pipeletPriority)) $pipeletPriority = 0;
			
			//construct a pipelet based on extracted information, and place in proper group
			if(!empty($pipeletId)){
				$pipeletGroups[$pipeletPriority][$pipeletId] = new OpenPipe_Pipelet_Base($pipeletId, $phase);
			}
		}
		
		//sort each groups array by pipelet id
		foreach($pipeletGroups as $key => $pipeletGroup){
			ksort($pipeletGroups[$key]);
		}
		//now that all groups are accounted for sort by priority
		krsort($pipeletGroups);
		
		//flatten all the segments to a single array
		foreach($pipeletGroups as $pipeletGroup){
			foreach($pipeletGroup as $pipelet){
				$pipelets[] = $pipelet;
			}	
		}
		
		return $pipelets;
	}
	
}