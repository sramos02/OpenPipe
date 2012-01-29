<?php


interface OpenPipe_Pipelet_Interface{
	
	function setId($id);
	function getId();
	
	function setPhase($phase);
	function getPhase();
	
	function setOutput($output);
	function getOutput();
	
}