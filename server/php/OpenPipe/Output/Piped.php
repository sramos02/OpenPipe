<?php


class OpenPipe_Output_Piped {
	
	
	public static function echoJsNow($output, $wrapTags=true, $outputBufferSize=null, $paddingCharacter=' '){
		$output = str_replace("\n", '', $output);
		if($wrapTags === true) $output = '<script type="text/javascript" >'.$output.'</script>';
		
		self::echoNow($output, $outputBufferSize, $paddingCharacter);
	}
	
	
	
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


function op_piped_echo($output, $outputBufferSize=null, $paddingCharacter = ' '){
	OpenPipe_Output_Piped::echoNow($output, $outputBufferSize, $paddingCharacter);
}


function op_piped_echo_js($output, $wrapTags=true, $outputBufferSize=null, $paddingCharacter=' '){
	OpenPipe_Output_Piped::echoJsNow($output, $wrapTags, $outputBufferSize, $paddingCharacter);
}