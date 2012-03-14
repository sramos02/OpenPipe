<?php
/**
*	Utility object which provides reusable output based services for HTTP Pipeline based systems
*
*	@author Sean Kenny
*	@package OpenPipe_Output
*	@version <version_id>
**/

class OpenPipe_Output_Piped {
	
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



/**
* Convenience function for OpenPipe_Output_Piped::echoNow($output, $outputBufferSize, $paddingCharacter)
*/
function op_piped_echo($output, $outputBufferSize=null, $paddingCharacter = ' '){
	OpenPipe_Output_Piped::echoNow($output, $outputBufferSize, $paddingCharacter);
}


/**
*	Convenience function for OpenPipe_Output_Piped::echoJsNow($output, $wrapTags, $outputBufferSize, $paddingCharacter);
*/
function op_piped_echo_js($output, $wrapTags=true, $outputBufferSize=null, $paddingCharacter=' '){
	OpenPipe_Output_Piped::echoJsNow($output, $wrapTags, $outputBufferSize, $paddingCharacter);
}