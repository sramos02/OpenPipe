<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fakebook extends CI_Controller {

	
	/* --- Main layout ---- */
	public function index()
	{
		$this->load->view('fakebook');
	}
	
	
	/* ---- Main content ---- */
	public function content_post_input(){
		$this->load->view('fakebook/content_post_input');
	}
	
	public function content_posts(){
		$this->load->view('fakebook/content_posts');
	}
	
	
	/* --- Posts ---- */
	public function post_comments(){
		$this->load->view('fakebook/post_comments');
	}
	
	
	
	/* ---- Header ---- */
	public function header(){
		$this->load->view('fakebook/header');
	}
	
	
	
	/* ---- Left sidebar ----*/
	public function sidebar_profile(){
		$this->load->view('fakebook/sidebar_profile');
	}
	
	public function sidebar_favorites(){
		$this->load->view('fakebook/sidebar_favorites');
	}
	
	public function sidebar_apps(){
		$this->load->view('fakebook/sidebar_apps');
	}
	
	public function sidebar_groups(){
		$this->load->view('fakebook/sidebar_groups');
	}
	
	public function sidebar_friends(){
		$this->load->view('fakebook/sidebar_friends');
	}
	
	public function sidebar_friends_on_chat(){
		$this->load->view('fakebook/sidebar_friends_on_chat');
	}
	
	
	
	
	/* --- Right sidebar ---- */
	public function sidebar_ads(){
		$this->load->view('fakebook/sidebar_ads');
	}
	
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */