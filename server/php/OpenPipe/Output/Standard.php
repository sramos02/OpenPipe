<?php


require_once('Interface.php');
require_once('Util.php');


class OpenPipe_Output_Standard implements OpenPipe_Output_Interface {
	
	protected $styles;
	protected $links;
	protected $scripts;
	protected $content;
	
	
	public function bootstrap(){
		$this->styles = array();
		$this->links = array();
		$this->scripts = array();
		$this->content = '';
	}
	
	
	public function preContent(){
		//nothing to do for standard based output
	}
	
	
	public function phaseStart($phase){
		//nothing to do for standard based output
	}
	
	public function content($content){
		if(is_string($content)){
			$id = 'op-container';
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
	
	
	public function phaseEnd($phase){ 
		//nothing to do for standard based output
	}
	
	
	public function postContent(){
		//nothing to do for standard based output
	}
	
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
	
	
	
	
	protected function injectHtml($id, $html){
		if($this->content == ''){
			$this->content = $html;
		}else{
			$this->content = preg_replace("/(<.*?pipelet-id=(?:\"$id\"|'$id').*?>)/ms", "\\1 $html", $this->content);
		}
	}
	
	
}