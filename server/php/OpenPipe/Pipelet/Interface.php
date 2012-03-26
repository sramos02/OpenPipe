<?php
/**
*	An interface defining a pipelet. A pipelet is an atomic entity with an pipe based HTML layout. A pipelet is essentially a piece of content that is loaded 
*	in a priority based sequential fashion and outputted immediately to the end client browser, without having to wait for other pipelets or sub pipelets to be
*	loaded as well. A pipelets main purposed is to deliver content in modular packages that increase the, 'perceived', load time of an HTML based PHP application.
*
*	A pipelet at its core has an ID, phase, and output.
*
*	- An id is derived either from the parent layout of parent pipelet. The id is used to identify and load content from a PHP OpenPipe adapter (OpenPipe_Adapter_Interface)
*	- A phase is used to determine the timing and priority of a pipelet when it is received by an end client browser
*	- The output is any data gathered from the OpenPipe adapter (utilizing the pipelet id). This data is subsequently piped to an end client browser
*
*	@author Sean Kenny @author Sean Kenny <skenny214@gmail.com>|<kennys1@southernct.edu>
*	@package OpenPipe_Pipelet
*	@license (c) 2011-2012 Sean Kenny, Southern Connecticut State University (SCSU).
*	@version 1.0.0
**/

interface OpenPipe_Pipelet_Interface{
	
	/**
	*	Sets the id of the pipelet (used to determine what content to gather from a Pipe Adapter)
	*	@param string $id a unique identifier for the pipelet that will signify importance to the client adapter and allow data to be looked up/generated accordingly
	*/
	function setId($id);
	
	/**
	*	Returns the current set pipelet id
	*/
	function getId();
	
	
	/**
	*	Sets the phase of the pipelet (used to determine loading priorities and sequences)
	*	
	*	@param	int|string phase to set. Lower numbers are higher priority (1), than higher numbers (999)
	*/
	function setPhase($phase);
	
	/**
	*	Return the current set phase number
	*/
	function getPhase();
	
	
	/**
	*  Set the output that has been gathered for this pipelet from a Pipelet_Adapter
	*
	*  param string $output the output string that has been generated/gathered for this given pipelet
	*/
	function setOutput($output);
	
	
	/**
	*	Return the output that is currently set for the pipelet
	*/
	function getOutput();
	
}