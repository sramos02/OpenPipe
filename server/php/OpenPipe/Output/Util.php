<?php
/**
*	Utility object which provides reusable output based services for HTTP Pipeline based systems
*
*	@author Sean Kenny @author Sean Kenny <skenny214@gmail.com>|<kennys1@southernct.edu>
*	@package OpenPipe_Output
*	@license (c) 2011-2012 Sean Kenny, Southern Connecticut State University (SCSU).
*	@version 1.0.0
**/

class OpenPipe_Output_Util  {
	
	/**
	*	Given an html string extract the link information from the raw data and return
	*	@param $html string the html string to extract script tags from
	*	@return array array of strings containing link tags found within the html string
	*/
	public static function extractLinkTags(&$html){
		preg_match_all('/<link.*?\/>/ms', $html, $matches, PREG_SET_ORDER);
		$html = preg_replace('/<link.*?\/>/ms', '', $html);
		
		$links = array();
		foreach($matches as $match){
			$links[] = $match[0];
		}

		return $links;
	}
	
	
	/**
	*	Given an html string extract the style information from the raw data and return
	*	@param $html string the html string to extract style tags from
	*	@return array array of strings containing style tags found within the html string
	*/
	public static function extractStyleTags(&$html){
		preg_match_all('/<style.*?>.*?<\/style>/ms', $html, $matches, PREG_SET_ORDER);
		$html = preg_replace('/<style.*?>.*<\/style>/ms', '', $html);
		
		$styles = array();
		foreach($matches as $match){
			$styles[] = $match[0];
		}
		
		return $styles;
	}
	
	
	/**
	*	Given an html string extract the  information from the raw data and return
	*	@param $html string the html string to extract script tags from
	*	@return array array of strings containing script tags found within the html string
	*/
	public static function extractScriptTags(&$html){
		preg_match_all('/<script.*?>.*?<\/script>/ms', $html, $matches, PREG_SET_ORDER);
		$html = preg_replace('/<script.*?>.*<\/script>/ms', '', $html);
		
		$scripts = array();
		foreach($matches as $match){
			$scripts[] = $match[0];
		}
		
		return $scripts;
	}
	
	
		
	
	/**
	*	Outputs javascript data in piped format. Piped format implies minimized and able to be placed in a pipe JavaScript array
	*
	*	@param string $output the output data (javascript) to be wrapped in a javascript tagged and echoed immediately
	*	@param boolean $wrapTags wrap the output in a script opening and closing tag
	**/
	public static function echoJsNow($output, $wrapTags=true, $outputBufferSize=null, $paddingCharacter=' '){
		$output = str_replace("\n", '', $output);
		if($wrapTags === true) $output = '<script type="text/javascript" >'.$output.'</script>';
		
		self::echoNow($output, $outputBufferSize, $paddingCharacter);
	}
	
	
	/**
	*   Highly reusable output method which echos data NOW. by NOW we mean in an intelligent way that takes into account output buffering in PHP
	*	as well as browser based deferred display of data (until data is of x bytes). Using this utility method one should not have to worry about how 
	*	to immediately send data to an end client browser NOW.
	*	
	*	@param string $output the data to output NOW!
	*	@param	null|int $outputBufferSize the output buffer currently in use. if a string is not of an output buffer length it will be padded to meet the minimum buffer size. If not provided this value will be looked up from the PHP ini configuration value 
	*	@param string $paddingCharacter the character to pad output with if the buffer is larger than the data to output. Default to a space  - ' '
	*	@return void doesn't return anything - output data directly. Catch in output buffer (ob_start()) if you need it for some weird reason.
	*/
	public static function echoNow($output, $outputBufferSize=null, $paddingCharacter = ' '){
		
		//if the output buffer is null, then attempt to get it from php ini
		if($outputBufferSize === null){
			$outputBufferSize = @ini_get('output_buffering');
			if($outputBufferSize == 'Off') $outputBufferSize = 0;
		}
		
		//now that we know the buffer check to see how much we need to pad the string that is to be outputted
		$bufferSpace = $outputBufferSize - strlen($output);
		if($bufferSpace > 0){
			$output = $output.str_repeat($paddingCharacter, $bufferSpace);
		}
		
		//echo the string (with possible padding), then flush!
		echo $output;
		flush();
	}
	
}
