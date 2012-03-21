<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Openpipe extends CI_Controller {


	public function index()
	{
		$this->load->view('openpipe');
	}
	
	
	public function pipelet1(){
		$view = $this->load->view('pipelet1');
	}
	
	public function pipelet2(){
		$this->load->view('pipelet2');
	}
	
	public function pipelet3(){
		echo $this->load->view('pipelet3');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */