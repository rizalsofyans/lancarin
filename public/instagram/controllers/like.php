<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class like extends MX_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->tb_accounts = INSTAGRAM_ACCOUNTS;
		$this->tb_activities = INSTAGRAM_ACTIVITIES;
		$this->tb_activities_log = INSTAGRAM_ACTIVITIES_LOG;
		$this->module = get_class($this);
		$this->module_name = lang("instagram_accounts");
		$this->module_icon = "fa fa-instagram";
		$this->load->model($this->module.'_model', 'model');
	}

	public function index(){
		$data = array(
			"accounts" =>  $this->model->fetch("*", $this->tb_accounts)
		);
		$this->template->build('like/index', $data);
	}

	public function settings($ids = ""){
		$data = array(
			"module" => $this->module
		);
		$this->template->build('like/settings', $data);
	}

	public function log(){
		$data = array(
			"module" => $this->module
		);
		$this->template->build('like/log', $data);
	}

	public function popup($type){
		switch ($type) {
			case 'tag':
				$this->load->view($this->module."/add_tag");
				break;

			case 'comment':
				$this->load->view($this->module."/add_comment");
				break;

			case 'direct_message':
				$this->load->view($this->module."/add_direct_message");
				break;

			case 'username':
				$this->load->view($this->module."/add_username");
				break;
			
			case 'location':
				$this->load->view($this->module."/add_location");
				break;

			case 'backlist_tag':
				$this->load->view($this->module."/add_backlist_tag");
				break;

			case 'backlist_username':
				$this->load->view($this->module."/add_backlist_username");
				break;

			case 'backlist_keyword':
				$this->load->view($this->module."/add_backlist_keyword");
				break;

			default:
				return false;
				break;
		}
	}
}