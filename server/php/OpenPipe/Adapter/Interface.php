<?php
/**
*	An interface defining an adapter which bridges php based applications with OpenPipe. OpenPipe will call the adapter to load layouts and pipelets. 
*	In essence the adapter is responsible for making sure that the php based application is instantiated, bootstrapped, and run appropriately to obtain
* 	the request element (either layout or pipelet)
*
*	@author Sean Kenny @author Sean Kenny <skenny214@gmail.com>|<kennys1@southernct.edu>
*	@package OpenPipe_Adapter
*	@license (c) 2011-2012 Sean Kenny, Southern Connecticut State University (SCSU).
*	@version <version_id>
**/

interface OpenPipe_Adapter_Interface {
	
	/**
	*	return output from the php application for immediate piping
	*	@param OpenPipe_Pipelet_Interface $pipelet a pipelet which supplies information on 
	*	@return mixed implementors are free to return what they will
	*/
	function getOutput(OpenPipe_Pipelet_Interface $pipelet);
	
	/**
	*	called once during the initialization of an OpenPipe runner
	*/
	function bootstrap();
	
	/**
	*	called once during the shut down of an OpenPipe runner
	*/
	function clean();
}
