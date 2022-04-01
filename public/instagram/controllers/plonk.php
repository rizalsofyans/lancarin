<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class plonk extends MX_Controller {
	public function __construct(){
		parent::__construct();
		
	}
	
	public function index2($userIG){
		$instagram_account = $this->model->get("*", 'instagram_accounts', "uid = '".session("uid")."' AND status = 1", "rand()");
		$ig   = new InstagramAPI($instagram_account->username, $instagram_account->password, $instagram_account->proxy);
		$userinfo = $ig->get_userinfo($userIG);
		$follower = $userinfo->follower_count;
		$resp = $ig->search_media($userIG, 'username');
		$o=[];
		$en=[];
		if(!empty($resp->items)){
			foreach($resp->items as $items){
				$comment_count=isset($items->comment_count)?$items->comment_count:0;
				$like_count=isset($items->like_count)?$items->like_count:0;
				$engagement = ($comment_count+$like_count)/$follower*100;
				$o[] = [
					'comment'=> $comment_count,
					'like'=> $like_count,
					'follower'=> $follower,
					'engagement'=> round($engagement,2).'%'
				];
				$en[] = $engagement;
			}
		}
		print_r($o);
		echo 'avg engagement: '. round(array_sum($en)/count($en),2).'%';
	}

}