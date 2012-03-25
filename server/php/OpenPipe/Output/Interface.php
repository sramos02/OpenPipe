<?php
/**
*	An interface defining the output mechanism for openpipe. This abstraction allows for the implementing class to handle individual pipelet output appropriatley
*
*	@author Sean Kenny @author Sean Kenny <skenny214@gmail.com>|<kennys1@southernct.edu>
*	@package OpenPipe_Pipelet
*	@license (c) 2011-2012 Sean Kenny, Southern Connecticut State University (SCSU).
*	@version <version_id>
**/


interface OpenPipe_Output_Interface {
	
	
	public function bootstrap();
	
	public function preContent();
	
	public function phaseStart($phase);
	public function content($content);
	public function phaseEnd($phase);
	
	public function postContent();
	
	public function clean();
	
}