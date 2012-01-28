<?php


interface OpenPipe_Pipelet_Interface{
	
	function setId(string $id);
	function getId();
	
	function setPhase(int $phase);
	function getPhase();
	
	function loadHtml(string $html);
	
	function pipe();
	
}