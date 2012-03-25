<?php

require_once('Interface.php');
require_once('Util.php');

class OpenPipe_Output_Piped implements OpenPipe_Output_Interface {
	
	protected $jsPath;
	
	
	public function __construct($jsPath){
		$this->jsPath = $jsPath;
	}

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
	
	
	public function phaseStart($phase){
		//OpenPipe_Output_Util::echoJsNow("op.phaseStart($phase);");
	}
	
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
	
	public function phaseEnd($phase){
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
	
	public function clean(){
		//we're all clean!
	}
	
		
	
}