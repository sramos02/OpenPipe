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
		$header  = '<!DOCTYPE html><html><head>';
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
		$this->pipe($content);
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
	
	public function cleanUp(){
		//we're all clean!
	}
	
	
	
	
	/**
	*	Given an OpenPipe_Pipelet_Interface based object extract the relevant OpenPipe Javascript components and output as JavaScript to the end client browser
	*	@param OpenPipe_Pipelet_Interface $pipelet pipelet to extract data from and send as OpenPipe client library loadable JSON data
	*	@return void
	*/
	protected function pipe($content){
		
		
		if(is_string($content)){
			$id = 'op-container';
			$html = $content;
		}else{
			$id = $content->getId();
			$html = $content->getOutput();
		}
		
		$css = $this->extractCssJsonArray($html);
		$js = $this->extractJsJsonArray($html);
		
		$html = str_replace("'", "\\'", $html);
		

		OpenPipe_Output_Util::echoJsNow("op.load({'id': '$id', 'html': '$html', 'css': $css, 'scripts': $js});");

	}
	
	
	
	
	/**
	*	Given an OpenPipe_Pipelet_Interface object extract the css information from the raw data and encode in a JSON array
	*	@param OpenPipe_Pipelet_Interface $pipelet pipelet to extract CSS data and JSON encode as an array of CSS 
	*	@return string JSON encoded array of CSS stylesheet extracted from the $pipelet output
	*/
	protected function extractCssJsonArray(&$html){
		preg_match_all('/<style.*?>.*?<\/style>/', $html, $matches, PREG_SET_ORDER);
		preg_replace('/<style.*?>.*<\/style>/', '', $html);
		
		$css = array();
		foreach($matches as $match){
			$css[] = $match[0];
		}
		
		return json_encode($css);
	}
	
	/**
	*	Given an OpenPipe_Pipelet_Interface object extract the JavaScript information from the raw data and encode in a JSON array
	*	@param OpenPipe_Pipelet_Interface $pipelet pipelet to extract JavaScript data and JSON encode as an array of JavaScript data
	*	@return string JSON encoded array of JavaScript tags extracted from the $pipelet output
	*/	
	protected function extractJsJsonArray(&$html){
		preg_match_all('/<script.*?>.*?<\/script>/', $html, $matches, PREG_SET_ORDER);
		preg_replace('/<script.*?>.*<\/script>/', '', $html);
		
		$js = array();
		foreach($matches as $match){
			$js[] = $match[0];
		}
		
		return json_encode($js);
	}
	
	
		
	
}