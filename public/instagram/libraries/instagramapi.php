<?php
require "instagram-php/autoload.php";
if(file_exists(APPPATH."../public/instagram/libraries/instagram_activity.php")){
    require "instagram_activity.php";
}

class instagramapi{
    public $username;
    public $password;
    public $proxy;
    public $ig;
    public $twoFactorIdentifier = NULL;

    public function __construct($username = null, $password = null, $proxy = null, $verificationCode = null){
        if(file_exists(APPPATH."../public/instagram/libraries/instagram_activity.php")){
            $this->activity = new instagram_activity($this);
        }

        if($username != null && $password != null){
            $password = encrypt_decode($password);
            $this->username = $username;
            $this->password = $password;
            $this->proxy = $proxy;
            
            $ig = new \InstagramAPI\Instagram(false, false, [
                'storage'    => 'mysql',
                'dbhost'     => DB_HOST,
                'dbname'     => DB_NAME,
                'dbusername' => DB_USER,
                'dbpassword' => DB_PASS,
                'dbtablename'=> "instagram_sessions"
            ]);

            $ig->setVerifySSL(false);

            if(!empty($proxy)){
                $ig->setProxy($proxy);
            }
            
            try {
                $loginResponse = $ig->login($username, $password);
                if (!is_null($loginResponse) && $loginResponse->isTwoFactorRequired()) {
                    if($verificationCode != ""){
                        $twoFactorIdentifier = $loginResponse->getTwoFactorInfo()->getTwoFactorIdentifier();
                        $this->twoFactorIdentifier = $twoFactorIdentifier;
                        $ig->finishTwoFactorLogin($username, $password, $twoFactorIdentifier, $verificationCode);
                    }else{
                        return ms(array(
                            "status"   => "error",
                            "callback" => '<script type="text/javascript">Instagram.TwoFactorLogin();</script>',
                            "message"  => lang("please_enter_verify_code_to_add_instagram_account")
                        ));                       
                    }
                }
            } catch (\Exception $e) {
                $this->checkpoint($e);
                throw new \InvalidArgumentException(Instagram_Get_Message($e->getMessage()));
            }   

            $this->ig = $ig;
        }
    }

    function checkpoint($e){
        $challenge_type = Instagram_Get_Message($e->getMessage());
		$CI = &get_instance();
        if($challenge_type == "challenge_required" 
            || $challenge_type == "login_required" 
            || $challenge_type == "checkpoint_required" 
            || strpos($challenge_type, "The password you entered is incorrect") !== false
            || strpos($challenge_type, "Challenge required") !== false){
            
            $CI->db->update(INSTAGRAM_ACCOUNTS, array("status" => 0), "username = '{$this->username}'");
            $CI->db->delete('instagram_sessions', "username = '{$this->username}'");
        }
		if(get_option('email_problem_reporting', 1)==1){
			$q = $CI->db->select('a.uid, a.id as account_id')->from(INSTAGRAM_ACCOUNTS .' a')->join(USERS .' b', 'a.uid=b.id')->where('email_error',1)->where('a.username', $this->username)->get();
			if($q->num_rows()>0){
				$CI->db->insert('general_email_error', ['uid'=>$q->row()->uid, 'account_id'=>$q->row()->account_id, 'description'=>$e->getMessage(), 'type'=>$challenge_type]);
			}
		}
    }

    function get_current_user(){
        try {
            $user = $this->ig->account->getCurrentUser();
            return json_decode($user);
        } catch (\Exception $e) {
            $this->checkpoint($e);
            ms(array(
                "status"  => "error",
                "message" => Instagram_Get_Message($e->getMessage())
            ));
        }   
    }

    function post($data){
        $spintax  = new Spintax();
        $data     = (object)$data;
        $response = array();
        try {
            $data->data = (object)json_decode($data->data);
            $media      = $data->data->media;
            $caption    = @$spintax->process(Instagram_Caption($data->data->caption));
            $comment    = @$spintax->process(Instagram_Caption($data->data->comment));
            $watermark = (isset($data->data->watermark) && $data->data->watermark)?true:false;

            switch ($data->type) {
                case 'photo':
                    if(check_image($media[0])){

                        //Auto Resize
                        //$media[0] = instagram_image_handlers($media[0], "photo", $data->uid, $watermark);
                        $media[0] = instagram_image_handlers($media[0], "photo", $data->account, $watermark);
						
                        $response = $this->ig->timeline->uploadPhoto(get_path_file($media[0]), array("caption" => $caption));
                        $response = json_decode($response);
                    }else{
                        $response = $this->ig->timeline->uploadVideo(get_path_file($media[0]), array("caption" => $caption));
                        $response = json_decode($response);
                    }
                    
                    //Add first comment
                    if($comment != ""){
                        $this->ig->media->comment($response->media->pk, $comment);
                    }
                    break;

                case 'story':
                    if(check_image($media[0])){

                        //Auto Resize
                        //$media[0] = instagram_image_handlers($media[0], "story", $data->uid, $watermark);
                        $media[0] = instagram_image_handlers($media[0], "story", $data->account, $watermark);

						$response = $this->ig->story->uploadPhoto(get_path_file($media[0]), array("caption" => $caption));
                        $response = json_decode($response);
                    }else{
                        $response = $this->ig->story->uploadVideo(get_path_file($media[0]), array("caption" => $caption));
                        $response = json_decode($response);
                    }


                    //Add first comment
                    if($comment != ""){
                        $this->ig->media->comment($response->media->pk, $comment);
                    }
                    break;

                case 'carousel':
                    $medias = array();
                    foreach ($media as $item) {
                        $image_info = get_image_size($item);
                        if(!empty($image_info)){

                            //Auto Resize
                            //$item = instagram_image_handlers($item, "carousel", $data->uid, $watermark);
                            $item = instagram_image_handlers($item, "carousel", $data->account, $watermark);

                            $medias[] = array(
                                'type' => 'photo',
                                'file' => get_path_file($item)
                            );
                        }else{
                            $file_info = get_file_info($item);
                            if(!empty($file_info) && isset($file_info['extension']) && isset($file_info['extension']) == "mp4"){
                                $medias[] = array(
                                    'type' => 'video',
                                    'file' => get_path_file($item)
                                );
                            }
                        }
                    }

                    $response = $this->ig->timeline->uploadAlbum($medias, array("caption" => $caption));
                    $response = json_decode($response);
                    //Add first comment
                    if($comment != ""){
                        $this->ig->media->comment($response->media->pk, $comment);
                    }
                    break;
            }

            return $response->media;

        } catch (Exception $e) {
            $this->checkpoint($e);
            return array(
                "status"  => "error",
                "message" => Instagram_Get_Message($e->getMessage())
            );
        }
    }

    function search_media($keyword, $type, $next_max_id=null){
        try {
            switch ($type) {
                case 'username':
                    $id = $this->ig->people->getUserIdForName($keyword);
                    //$response = $this->ig->timeline->getUserFeed($id, $this->rankToken());
					$response = $this->ig->timeline->getUserFeed($id, $next_max_id);
                    $response = json_decode($response);
                    return $response;
                    break;
                
                default:
                    $response = $this->ig->hashtag->getFeed($keyword, $this->rankToken(), $next_max_id);
                    $response = json_decode($response);
                    return $response;
                    break;
            }
        } catch (Exception $e) {
            $this->checkpoint($e);
            return false;
        }
    }

    function search_tag($keyword){
        try {
            $response = $this->ig->hashtag->search($keyword, array(), $this->rankToken());
            $response = json_decode($response);
            if(isset($response->results) && !empty($response->results)){
                return $response->results;
            }
            return false;
        } catch (Exception $e) {
            $this->checkpoint($e);
            return false;
        }
    }
	
    function list_collection($maxId=null){
        try {
            $response = $this->ig->collection->getList($maxId);
            $response = json_decode($response);
            if(isset($response->items) && !empty($response->items)){
                return $response;
            }
            return false;
        } catch (Exception $e) {
            $this->checkpoint($e);
            return false;
        }
    }
	
    function get_collection_feed($collection_id, $maxId=null){
        try {
            $response = $this->ig->collection->getFeed($collection_id, $maxId);
            $response = json_decode($response);
            if(isset($response->items) && !empty($response->items)){
                return $response;
            }
            return false;
        } catch (Exception $e) {
            $this->checkpoint($e);
            return false;
        }
    }

	function get_saved_feed($maxId=null){
        try {
            $response = $this->ig->media->getSavedFeed($maxId);
            $response = json_decode($response);
            if(isset($response->items) && !empty($response->items)){
                return $response;
            }
            return false;
        } catch (Exception $e) {
            $this->checkpoint($e);
            return false;
        }
    }
	
	
	
    function search_username($keyword){
        try {
            $response = $this->ig->people->search($keyword, array(), $this->rankToken());
            $response = json_decode($response);
            if(isset($response->users) && !empty($response->users)){
                return $response->users;
            }
            return false;
        } catch (Exception $e) {
            $this->checkpoint($e);
            return false;
        }
    }

    function search_location($keyword){
        try {
            $response = $this->ig->location->findPlaces($keyword, array(), $this->rankToken());
            $response = json_decode($response);
            if(isset($response->items) && !empty($response->items)){
                return $response->items;
            }
            return false;
        } catch (Exception $e) {
            $this->checkpoint($e);
            return false;
        }
    }

    function get_followers($getAll = false, $userId = null, $maxId = null, $maxCount = 1000, $rankToken=null, $returnPaging=false){
        if($userId == "") $userId = $this->ig->account_id;
        try {
			$rankToken = empty($rankToken)?$this->rankToken(): $rankToken;
            if($getAll){
                $users = array();
                do {
                    $response = $this->ig->people->getFollowers($userId, $rankToken, null, $maxId);
                    $response = json_decode($response);
                    $users = array_merge($users, $response->users);
					$maxId = (isset($response->next_max_id) && !empty($response->next_max_id))?$response->next_max_id:null;
                } while (!empty($maxId) && $maxCount > count($users));
				if($returnPaging){
					return ['users'=>$users, 'next_max_id'=>$maxId, 'rank_token'=>$rankToken];
				}else{
					return $users;
				}
            }else{
                $response = $this->ig->people->getFollowers($userId, $rankToken, null, $maxId);
                $response = json_decode($response);
                if(isset($response->users) && !empty($response->users)){
                    return $response->users;
                }
            }
            return false;
        } catch (Exception $e) {
            $this->checkpoint($e);
            return false;
        }
    }

    function get_following($getAll = false, $userId = null, $maxId = null, $maxCount = 1000, $rankToken=null, $returnPaging=false){
        if($userId == "") $userId = $this->ig->account_id;
        try {
			$rankToken = empty($rankToken)?$this->rankToken(): $rankToken;
            if($getAll){
                $users = array();
                do {
					$response = $this->ig->people->getFollowing($userId, $rankToken, null, $maxId);
                    $response = json_decode($response);
                    $users = array_merge($users, $response->users);
					$maxId = (isset($response->next_max_id) && !empty($response->next_max_id))?$response->next_max_id:null;
                } while (!empty($maxId) && $maxCount > count($users));
                if($returnPaging){
					return ['users'=>$users, 'next_max_id'=>$maxId, 'rank_token'=>$rankToken];
				}else{
					return $users;
				}
            }else{
                $response = $this->ig->people->getFollowing($userId, $rankToken, null, $maxId);
                $response = json_decode($response);
                if(isset($response->users) && !empty($response->users)){
                    return $response->users;
                }
				
				
            }
            return false;
        } catch (Exception $e) {
            $this->checkpoint($e);
            return false;
        }
    }
	
	function get_timeline($maxId = null, $full = false, $maxCount = 80, $returnPaging=false){
        try {

            if(!$full){
            
                $response = $this->ig->timeline->getTimelineFeed($maxId);
                $response = json_decode($response);
                return $response;
            
            }else{

                $feed = array();
                do {
                    $response = $this->ig->timeline->getTimelineFeed($maxId);
                    $response = json_decode($response);
                    $feed = array_merge($feed, $response->items);
					$maxId = (isset($response->next_max_id) && !empty($response->next_max_id))?$response->next_max_id:null;
				} while (!empty($maxId) && $maxCount > count($feed));
				if($returnPaging){
					return ['feed'=>$feed, 'next_max_id'=>$maxId];
				}else{
					return $feed;
				}
            }

            return false;
        } catch (Exception $e) {
            $this->checkpoint($e);
            return false;
        }
    }


    function get_feed($userId = null, $maxId = null, $full = false, $maxCount = 80, $returnPaging=false){
        if($userId == "") $userId = $this->ig->account_id;
        try {

            if($full){
            
                $response = $this->ig->timeline->getUserFeed($userId, $maxId);
                $response = json_decode($response);
                return $response;
            
            }else{

                $feed = array();
                do {
                    $response = $this->ig->timeline->getUserFeed($userId, $maxId);
                    $response = json_decode($response);
                    $feed = array_merge($feed, $response->items);
					$maxId = (isset($response->next_max_id) && !empty($response->next_max_id))?$response->next_max_id:null;
				} while (!empty($maxId) && $maxCount > count($feed));
				if($returnPaging){
					return ['feed'=>$feed, 'next_max_id'=>$maxId];
				}else{
					return $feed;
				}
            }

            return false;
        } catch (Exception $e) {
            $this->checkpoint($e);
            return false;
        }
    }

    function get_feed_by_tag($tag, $maxId = null, $maxCount = 50){
        try {
            $items = array();
            do {
                $response = $this->ig->hashtag->getFeed($tag, $this->rankToken(), $maxId);
                $response = json_decode($response);
                
                if(isset($response->ranked_items)){
                    $items = array_merge($items, $response->ranked_items);
                }else if(isset($response->items)){
                    $items = array_merge($items, $response->items);
                }

            } while (isset($response->next_max_id) && !is_null($maxId = $response->next_max_id) && $maxCount > count($items));

            return $items;
        } catch (Exception $e) {
            $this->checkpoint($e);
            return false;
        }
    }

    function get_feed_by_location($tag, $maxId = null, $maxCount = 30){
        try {
            $items = array();
            do {
                $response = $this->ig->location->getFeed($tag, $this->rankToken(), $maxId);
                $response = json_decode($response);
                if(isset($response->ranked_items)){
                    $items = array_merge($items, $response->ranked_items);
                }else if(isset($response->items)){
                    $items = array_merge($items, $response->items);
                }

            } while (isset($response->next_max_id) && !is_null($maxId = $response->next_max_id) && $maxCount > count($items));
            return $items;
        } catch (Exception $e) {
            $this->checkpoint($e);
            return false;
        }
    }
	
	function get_comments_replies($mediaId, $commentId, $maxId = null, $maxCount =10){
		try {
			$comments = [];
			do {
                $response = $this->ig->media->getCommentReplies($mediaId, $commentId, ['max_id'=>$maxId]);
				//$maxId = $response->getNextMaxId();
                $response = json_decode($response);
				
                if(isset($response->child_comments) && !empty($response->child_comments)){
                    $comments = array_merge($comments, $response->child_comments);
                }
				//sleep(2);
            } while (!empty($maxId) && $maxCount > count($comments));
			return $comments;
		} catch (Exception $e) {
            $this->checkpoint($e);
            return false;
        }
	}

    function get_comments($mediaId, $maxId = null, $expect_me = true, $maxCount = 80, $returnPaging=false){
        try {
            $comments = array();
            do {
                //$response = $this->ig->media->getComments($mediaId, ['max_id'=>$maxId]);
                $response = $this->ig->media->getComments($mediaId, ['min_id'=>$maxId]);
				//$maxId = $response->getNextMaxId();
				$maxId = $response->getNextMinId();
                $response = json_decode($response);
				
                if(isset($response->comments) && !empty($response->comments)){
					foreach($response->comments as $com){
						$comments[] = $com;
						if(isset($com->num_tail_child_comments) && !empty($com->num_tail_child_comments)){
							$replies = $this->get_comments_replies($mediaId, $com->pk);
							if(!empty($replies)){
								$comments = array_merge($comments, $replies);
							}
						}
					}
                    //$comments = array_merge($comments, $response->comments);
                }
				//sleep(2);
            } while (!empty($maxId) && $maxCount > count($comments));
			
            if(!empty($comments)){
                if($expect_me){
                    $comments_tmp = array();

                    foreach ($comments as $value) {
                        if($value->user_id != $this->ig->account_id){
                            $comments_tmp[] = $value;
                        }
                    }

                    $comments= $comments_tmp;
                }
				
				if($returnPaging){
					return ['comments'=>$comments, 'next_min_id'=>$maxId];
				}else{
					return $comments;
				}
				
				
            }else{
                return false;
            }
        } catch (Exception $e) {
            $this->checkpoint($e);
            return false;
        }
    }

    function get_likers($mediaId, $expect_me = true){
        try {
            $response = $this->ig->media->getLikers($mediaId);
            $response = json_decode($response);
            if(isset($response->users) && !empty($response->users)){
                $users = $response->users;
                if($expect_me){

                    $users_tmp = array();

                    foreach ($users as $value) {
                        if($value->pk != $this->ig->account_id){
                            $users_tmp[] = $value;
                        }
                    }

                    return $users_tmp;

                }else{
                    return $users;
                }

                
            }
            return false;
        } catch (Exception $e) {
            $this->checkpoint($e);
            return false;
        }
    }

	function get_userid_from_name($username){
		try {
            $response = $this->ig->people->getUserIdForName($username);
            $response = json_decode($response);
            if(!empty($response)){
                return $response;
            }
            return false;
        } catch (Exception $e) {
            $this->checkpoint($e);
            return false;
        }
	}
	
    function get_userinfo($userId = null){
        if($userId == "") $userId = $this->ig->account_id;
        try {
            //$response = $this->ig->people->getInfoById($userId);
            $response = is_numeric($userId)?$this->ig->people->getInfoById($userId):$this->ig->people->getInfoByName($userId);
            $responseObj = json_decode($response);
			if(empty($responseObj)){
				$responseObj = (object)['user'=>$response->getUser()];
			}
            if(isset($responseObj->user) && !empty($responseObj->user)){
                return $responseObj->user;
            }
            return false;
        } catch (Exception $e) {
            $this->checkpoint($e);
            return false;
        }
    }
	
    function get_mediainfo($mediaId){
        try {
            $response = $this->ig->media->getInfo($mediaId);
            $response = json_decode($response);
            if(isset($response->items[0]) && !empty($response->items[0])){
                return $response->items[0];
            }
            return false;
        } catch (Exception $e) {
            $this->checkpoint($e);
            return false;
        }
    }

    function get_friendships($userIds){
        try {
            $response = $this->ig->people->getFriendships($userIds);
            $response = json_decode($response);
            if(isset($response->friendship_statuses) && !empty($response->friendship_statuses)){
                return $response->friendship_statuses;
            }
            return false;
        } catch (Exception $e) {
            $this->checkpoint($e);
            return false;
        }
    }

    function get_friendship($userId){
        try {
            $response = $this->ig->people->getFriendship($userId);
            $response = json_decode($response);
            if(isset($response->status) && $response->status == "ok"){
                return $response;
            }
            return false;
        } catch (Exception $e) {
            $this->checkpoint($e);
            return false;
        }
    }

    function get_recent_activity_inbox(){
        try {
            $response = $this->ig->people->getRecentActivityInbox();
            $response = json_decode($response);
            if(isset($response->status) && $response->status == "ok"){
                $stories = array_merge($response->new_stories, $response->old_stories);
                $stories = array_reverse($stories);
                return $stories;
            }
            return false;
        } catch (Exception $e) {
            $this->checkpoint($e);
            return false;
        }
    }

    function comment($mediaId, $comment, $spin = true){
        try {
            if($spin){
                $spintax  = new Spintax();
                $comment = (object)$comment;
                if(is_object($comment)){
                    if(isset($comment->dont_spam)){
                        unset($comment->dont_spam);
                    }

                    $comment = array_values((array)$comment);
                    $comment = get_random_value($comment);
                }

                $comment = @$spintax->process($comment);
            }
            $response = $this->ig->media->comment($mediaId, $comment);
            $response = json_decode($response);
            return $response;
        } catch (Exception $e) {
            $response = json_decode($e->getResponse());
            $this->checkpoint($e);
            return $response;
        }
    }

    function like($mediaId, $module='feed_timeline', array $extraData=[]){
        try {
            $response = $this->ig->media->like($mediaId, $module, $extraData);
            $response = json_decode($response);
            return $response;
        } catch (Exception $e) {
            $response = json_decode($e->getResponse());
            $this->checkpoint($e);
            return $response;
        }
    }
	
    function unlike($mediaId, $module='feed_timeline', array $extraData=[]){
        try {
            $response = $this->ig->media->unlike($mediaId, $module, $extraData);
            $response = json_decode($response);
            return $response;
        } catch (Exception $e) {print_r($e);
            $response = json_decode($e->getResponse());
            $this->checkpoint($e);
            return $response;
        }
    }

    function follow($userId){
        try {
            $response = $this->ig->people->follow($userId);
            $response = json_decode($response);
            return $response;
        } catch (Exception $e) {
            $response = json_decode($e->getResponse());
            $this->checkpoint($e);
            return $response;
        }
    }


    function unfollow($userId){
        try {
            $response = $this->ig->people->unfollow($userId);
            $response = json_decode($response);
            return $response;
        } catch (Exception $e) {
            $response = json_decode($e->getResponse());
            $this->checkpoint($e);
            return $response;
        }
    }

    function delete_media($mediaId){
        try {
            $response = $this->ig->media->delete($mediaId);
            $response = json_decode($response);
            return $response;
        } catch (Exception $e) {
            $response = json_decode($e->getResponse());
            $this->checkpoint($e);
            return $response;
        }
    }

    function direct_message($userId, $message = null, $type = null, $file = null, $mediaId = null){
        try {
            $temp_userid = $userId;
            if(is_string($userId) || is_numeric($userId)){
                $userId = array('users' => array($userId));
            }

            $message = (array)$message;
            $spintax  = new Spintax();

            if(is_array($message)){
                if(isset($message['by'])){
                    unset($message['by']);
                }

                $message = array_values((array)$message);
                $message = get_random_value($message);
            }

            if(strpos($message, "{{username}}") !== false || strpos($message, "{{full_name}}") !== false){
                $userinfo = $this->ig->people->getInfoById($temp_userid);
                $userinfo = json_decode($userinfo);
                if($userinfo->status = "ok"){
                    $message = str_replace("{{username}}", "@".$userinfo->user->username, $message);
                    if($userinfo->user->full_name != ""){
                        $message = str_replace("{{full_name}}", $userinfo->user->full_name, $message);
                    }else{
                        $message = str_replace("{{full_name}}", "@".$userinfo->user->username, $message);
                    }
                }
            }

            $message = @$spintax->process($message);

            switch ($type) {
                case 'post':
                    $response = $this->ig->direct->sendPost($userId, $mediaId);
                    break;

                case 'photo':
                    $response = $this->ig->direct->sendPhoto($userId, $file);
                    break;
                
                default:
                    $response = $this->ig->direct->sendText($userId, $message);
                    break;
            }
            
            $response = json_decode($response);
            return $response;
        } catch (Exception $e) {
            $response = json_decode($e->getResponse());
            $this->checkpoint($e);
            return $response;
        }
    }

    function get_random_feed($userId = null, $filter = ""){
        $feeds = $this->get_feed($userId);
               
        if(!empty($feeds)){
            if($filter != ""){
                $feeds_tmp = array();
                foreach ($feeds as $key => $feed) {
                    if(isset($feed->$filter) && $feed->$filter != 0){
                        $feeds_tmp[] = $feed;
                    }
                }

                $feeds = $feeds_tmp;
                return get_random_value($feeds);
            }

            return get_random_value($feeds);
        }

        return false;
    }

    function rankToken(){
        $rankToken = \InstagramAPI\Signatures::generateUUID();
        return  $rankToken;
    }
	
}