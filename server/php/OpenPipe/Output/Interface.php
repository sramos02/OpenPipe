<?php

/**
* An interface defining the output mechanism for OpenPipe. This abstraction allows for the implementing class to handle individual pipelet output appropriately
* @author Sean Kenny <skenny214@gmail.com>
* @package OpenPipe_Pipelet
* @license (c) 2011-2012 Sean Kenny, Southern Connecticut State University (SCSU).
* @version 1.0.0
*/
interface OpenPipe_Output_Interface {
	
	/**
	* Allow implementor to setup/output any data before the content phase begins
	*/
	public function bootstrap();
	
	
	/**
	* Called immediately before any content is to be outputted via the associated content() method
	*/
	public function preContent();
	
	
	/**
	* Called when content is ready for output - This content is already generated HTML string
	* @param string $content html data
	*/
	public function content($content);
	
	/**
	* Called when an output phase is complete -A phase represents a layer of data (each layer of data can contain n number of deeper layers)
	* @param int $phase the number of the phase to mark complete
	*/
	public function phaseComplete($phase);
	
	
	/**
	* Called immediately after all data has been sent for output
	*/
	public function postContent();
	
	
	/**
	* Allows implementor to do any final cleanup/output - last step in the output process
	*/
	public function clean();
	
}