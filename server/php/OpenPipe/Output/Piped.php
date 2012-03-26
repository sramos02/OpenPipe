<?php
/**
*   Implementation of an OpenPipe output interface that sends data via an HTTP pipeline. 
*	This is done by loading the openpipe.js client library and associated libraries.
*	The output handler handles extracting pipelet html data, and transmitting it as packed JSON object -
*	which will be unpacked by the client openpipe.js library
*
*	@author Sean Kenny @author Sean Kenny <skenny214@gmail.com>|<kennys1@southernct.edu>
*	@package OpenPipe_Output
*	@license (c) 2011-2012 Sean Kenny, Southern Connecticut State University (SCSU).
*	@version 1.0.0
**/

require_once('Interface.php');
require_once('Util.php');

class OpenPipe_Output_Piped implements OpenPipe_Output_Interface {
	
	/**
	*	@var string the web path where the client openpipe.js library will reside
	*/
	protected $jsPath;
	
	
	/**
	*	Builds an Piped output object
	*/
	public function __construct($jsPath){
		$this->jsPath = $jsPath;
	}


	/**
	*	Sends an initial string of output to force php and the browser to display piped output immediately
	*/
	public function bootstrap(){
		//set a 1024 newline - this forces soutput to the browser to start
		echo str_repeat(" ",1024);
		flush();
	}
	

	/**
	*	Outputs the framework for an HTTP Pipeline HTML document - this is essentially html and Javascript libraries. Note the html is unclosed (no ending body and html tags)
	*/	
	public function preContent(){
		$header  = "<!DOCTYPE HTML>\n<html><head>";
		$header .= "<script type='text/javascript' src='{$this->jsPath}/libs/jquery.js' ></script>";
		$header .= "<script type='text/javascript' src='{$this->jsPath}/libs/underscore.js'></script>";
		$header .= "<script type='text/javascript' src='{$this->jsPath}/openpipe.js'></script>";
		$header .= '</head><body><div id="op-container"></div>';

		OpenPipe_Output_Util::echoNow($header);
	}
	
	
	
	/**
	*	Extracts and outputs data in an openpipe.js friendly way
	*	
	*	@param $content string the content to be piped immediately. If CSS/JS is contained within the content, this will be extracted and handled automatically
	*/
	public function content($content){
		
		if(is_string($content)){
			$id = 'op-container';
			$html = $content;
		}else{
			$id = $content->getId();
			$html = $content->getOutput();
		}
		
		$css = OpenPipe_Output_Util::extractStyleTags($html);
		$js = OpenPipe_Output_Util::extractScriptTags($html);
		
		$html = str_replace("'", "\\'", $html);
		$css = json_encode($css);
		$js = json_encode($js);
		

		OpenPipe_Output_Util::echoJsNow("op.load({'id': '$id', 'html': '$html', 'css': $css, 'scripts': $js});");
	}
	
	
	/**
	*	Handles a phase complete signal by sending the openpipe.js phaseComplete command to the client browser
	*/
	public function phaseComplete($phase){
		OpenPipe_Output_Util::echoJsNow("op.phaseComplete($phase);");
	}
	

	/**
	*	Outputs the closing framework elements for an HTTP Pipeline HTML document - sends shutdown (done) method for client library and close initially open body and html tags.
	*	@return void
	*/	
	public function postContent(){
		OpenPipe_Output_Util::echoJsNow('op.done();');
		OpenPipe_Output_Util::echoNow('</body></html>');		
	}
	
	
	/**
	*	This handler is always fresh and so clean clean
	*/
	public function clean(){
		//we're all clean!
	}
	
		
	
}