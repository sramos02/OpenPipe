<?php

require_once('Interface.php');
require_once('Util.php');

/**
*   Implementation of an OpenPipe output interface that sends data as a standard HTML document
*	Content pieces are used to construct a complete HTML document, placing CSS and JavaScript in proper
*	placement, and inject each content piece within a pipelet place holder on the server side. It's 
*	important to note that no javascript is required to complete output on the client web browser while
*	utilizing this output implementation
*	@author Sean Kenny <skenny214@gmail.com>|<kennys1@southernct.edu>
*	@package OpenPipe_Output
*	@license (c) 2011-2012 Sean Kenny, Southern Connecticut State University (SCSU).
*	@version 1.0.0
*/
class OpenPipe_Output_Standard implements OpenPipe_Output_Interface {
	
	/**
	*	@var array linear array of style tags extracted from content and stored as string data
	*/
	protected $styles;
	
	/**
	*	@var array linear array of link tags extracted from content and stored as string data
	*/
	protected $links;
	
	/**
	*	@var array linear array of script tags extracted from content and stored as string data
	*/
	protected $scripts;
	
	/**
	*	@var string main html content stored as string and injected piece by piece as new content becomes available
	*/
	protected $content;
	
	
	/**
	*	Setup all the variables that will be needed to generate proper output
	*/
	public function bootstrap(){
		$this->styles = array();
		$this->links = array();
		$this->scripts = array();
		$this->content = '';
	}
	
	
	/**
	*	because standard output does not send any output until the end (clean method). This method is not needed
	*/
	public function preContent(){
		//nothing to do for standard based output
	}
	
	
	/**
	*	takes content and builds an complete html document piece by piece
	*	@param string|OpenPipe_Pipelet_Interface $content the html content that will have data extracted and assigned for final output
	*/
	public function content($content){
		if(is_string($content)){
			$id = '';
			$html = $content;
		}else{
			$id = $content->getId();
			$html = $content->getOutput();
		}
		
		//get the link, style, and script tages in each content section
		$this->styles = array_merge($this->styles, OpenPipe_Output_Util::extractStyleTags($html));
		$this->links = array_merge($this->links, OpenPipe_Output_Util::extractLinkTags($html));
		$this->scripts = array_merge($this->scripts, OpenPipe_Output_Util::extractScriptTags($html));
		
		$this->injectHtml($id, $html);
	}
	
	
	/**
	*	because standard output does not send any output until the end (clean method). This method is not needed
	*/
	public function phaseComplete($phase){ 
		//nothing to do for standard based output
	}
	
	
	/**
	*	because standard output does not send any output until the end (clean method). This method is not needed
	*/
	public function postContent(){
		//nothing to do for standard based output
	}
	
	
	/**
	*	Takes all of the gathered output and send the final html document as part of this last step
	*/
	public function clean(){
	 	$finalOutput = "<!DOCTYPE HTML>\n<html><head>";
		
		//put the collected scripts before body close
		foreach($this->links as $link){
			$finalOutput .= $link;
		}
		
		//put the collected styles in the head;
		foreach($this->styles as $style){
			$finalOutput .= $style;
		}
		
		$finalOutput .= '</head><body>';
		$finalOutput .= $this->content;

		
		//put the collected scripts before body close
		foreach($this->scripts as $script){
			$finalOutput .= $script;
		}
		
		$finalOutput .= '</body></html>';
		
		echo $finalOutput;
	}
	
	
	
	/**
	*	Attempts to inject the given html data into the currently recorded data. The point of injection is determined by the id provided
	*	@param string $pipeletId the identifier for the pipelet that will have html content injected within it
	*	@param string $html the content that will be injected into the current gathered output
	*/
	protected function injectHtml($pipeletId, $html){
		
		//if the content is currently empty, no injection needs to take place. Just set as the content root
		if($this->content == ''){
			$this->content = $html;
			
		//if we have content, find the injection point and perform the string replacement with a regex
		}else{
			$this->content = preg_replace("/(<.*?pipelet-id=(?:\"$pipeletId\"|'$pipeletId').*?>)/ms", "\\1 $html", $this->content);
		}
	}
	
	
}