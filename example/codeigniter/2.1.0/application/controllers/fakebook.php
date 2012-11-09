<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fakebook extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->type = $this->input->get('type', Externalevent::WEBSERVICE);
    }
	
	/* --- Main layout ---- */
	public function index(){
		$this->load->view('fakebook');
	}
	
	
	/* ---- Main content ---- */
	public function content_post_input(){
		$this->load->view('fakebook/content_post_input');
	}
	
	public function content_posts(){
		$this->externalevent->execute($this->type, 50);
		$this->load->view('fakebook/content_posts');
	}
	
	
	/* --- Posts ---- */
	public function post_comments(){
		$this->externalevent->execute($this->type, 10);
		$this->load->view('fakebook/post_comments');
	}
	
	
	
	/* ---- Header ---- */
	public function header(){
		$this->load->view('fakebook/header');
	}
	
	
	
	/* ---- Left sidebar ----*/
	public function sidebar_profile(){
		$this->externalevent->execute($this->type, 1);
		$this->load->view('fakebook/sidebar_profile');
	}
	
	public function sidebar_favorites(){
		$this->externalevent->execute($this->type, 5);
		$this->load->view('fakebook/sidebar_favorites');
	}
	
	public function sidebar_apps(){
		$this->externalevent->execute($this->type, 5);
		$this->load->view('fakebook/sidebar_apps');
	}
	
	public function sidebar_groups(){
		$this->externalevent->execute($this->type, 10);
		$this->load->view('fakebook/sidebar_groups');
	}
	
	public function sidebar_friends(){
		$this->externalevent->execute($this->type, 25);
		$this->load->view('fakebook/sidebar_friends');
	}
	
	public function sidebar_friends_on_chat(){
		$this->externalevent->execute($this->type, 10);
		$this->load->view('fakebook/sidebar_friends_on_chat');
	}
	
	
	
	
	/* --- Right sidebar ---- */
	public function sidebar_ads(){
		$this->externalevent->execute($this->type, 10);
		$this->load->view('fakebook/sidebar_ads');
	}
	
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */