<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class analytics extends MX_Controller {
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
		$this->template->build('analytics/index', $data);
	}


	public function show($ids = ""){
		$account = $this->model->get("*", $this->tb_accounts, "ids = '{$ids}'");

		$proxy_data = get_proxy($this->tb_accounts, $account->proxy, $account);
		try {
			$ig = new InstagramAPI($account->username, $account->password, $proxy_data->use);

			//pr($ig->get_feed(),1);

			$userinfo = $ig->get_userinfo();
			//pr($userinfo,1);
			//$feeds = $ig->get_feed();

			$date = strtotime(date("Y-m-d", strtotime(NOW)));
			$data_audience = array(
				$date => array(
					"follower_count" => $userinfo->follower_count,
					"following_count" => $userinfo->following_count,
					"media_count" => $userinfo->media_count
				)
			);

			
			
			pr($data_audience,1);


		} catch (Exception $e) {
			$result = array(
				"ms" => "error",
				"message" => $e->getMessage()
			);
		}

		$data = array(
			"accounts" =>  $this->model->get("*", $this->tb_accounts)
		);
		$this->template->build('analytics/show', $data);
	}
}