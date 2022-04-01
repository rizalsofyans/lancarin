<?php
/**
* Checkpoint
*/
class Instagram_Checkpoint{
	
	public $username;
	public $password;
	public $security_code = NULL;
	public $proxy;
	public $phone_id;
	public $uuid;
	public $device_id;
	public $cookie_path;
	public $cookie_folder = APPPATH."../public/instagram/libraries/instagram-php/cookies";
	public $csrftoken = NULL;
	public $uid = NULL;
	public $checkpoint_url = NULL;

	function __construct($username, $password, $security_code, $proxy)
	{
		$this->security_code = $security_code;
		$this->username = $username;
		$this->password = $password;
		$this->proxy = $proxy;

		if(session("ig_".$username."_phone_id")){
			$this->phone_id = session("ig_".$username."_phone_id");
		}else{
			$phone_id = SignatureUtils::generateUUID(true);
			set_session("ig_".$username."_phone_id", $phone_id);
			$this->phone_id = $phone_id;
		}

		if(session("ig_".$username."_uuid")){
			$this->uuid = session("ig_".$username."_uuid");
		}else{
			$uuid = SignatureUtils::generateUUID(true);
			set_session("ig_".$username."_uuid", $uuid);
			$this->uuid = $uuid;
		}

		if(session("ig_".$username."_device_id")){
			$this->device_id= session("ig_".$username."_device_id");
		}else{
			$device_id = SignatureUtils::generateDeviceId(md5($username . $password));
			set_session("ig_".$username."_device_id", $device_id);
			$this->device_id = $device_id;
		}

		if(session("ig_".$username."_csrftoken")){
			$this->csrftoken = session("ig_".$username."_csrftoken");
		}

		if(session("ig_".$username."_uid")){
			$this->uid = session("ig_".$username."_uid");
		}

		if(session("ig_".$username."_checkpoint_url")){
			$this->checkpoint_url = session("ig_".$username."_checkpoint_url");
		}

		if (!file_exists($this->cookie_folder)) {
		    mkdir($this->cookie_folder, 0777, true);
		}

		$this->cookie_path = $this->cookie_folder ."/". (string) $username . ".dat";
	}

	public function get_current_user(){
		$url = "accounts/current_user/";
		$post = array(
			"edit" => "true",
			"_uuid" => $this->phone_id,
			"_uid" => $this->uid,
			"_csrftoken" => $this->csrftoken
		);

		$response = $this->request($url, $post);
		return $response;
	}

	public function verification_code($security_code){
		if($this->checkpoint_url != NULL){
			
			$url = substr($this->checkpoint_url, 1);
			$post = array("security_code" => $security_code);

			$response = $this->request($url, $post);
			
			if(file_exists($this->cookie_path) && isset($response[1]['logged_in_user'])){
				unlink($this->cookie_path);
			}
			
			return $response;
		}
	}

	public function request_verification_code($checkpoint_url){
		$url = substr($checkpoint_url,1);
		$post = array("choice" => 0);
		$response = $this->request($url, $post);

		if(isset($response[1]['status']) && $response[1]['status'] == "fail" && isset($response[1]['message']) && strpos($response[1]['message'], "0 is not one of the available choices") === false){
			throw new Exception($response[1]['message']);
		}
		
		if(isset($response[1]['message']) && strpos($response[1]['message'], "0 is not one of the available choices") !== false){
			$post = array("choice" => 1);
			$response = $this->request($url, $post);
		}

		return $response;
	}

	public function login($force = false){
		if($this->checkpoint_url == NULL){

			//CREATE NEW COOKIES
			unset_session("ig_".$this->username."_phone_id");
			unset_session("ig_".$this->username."_uuid");
			unset_session("ig_".$this->username."_device_id");
			unset_session("ig_".$this->username."_csrftoken");
			unset_session("ig_".$this->username."_uid");
			unset_session("ig_".$this->username."_checkpoint_url");
			if(file_exists($this->cookie_path)){
				unlink($this->cookie_path);
			}

			$fetch = $this->request("si/fetch_headers/?challenge_type=signup&guid=" . $this->uuid, NULL, true);
			preg_match("#Set-Cookie: csrftoken=([^;]+)#", $fetch[0], $token);

			if(!isset($token[0])){
				$token[0] = '';
			}

			$post = array( 
				"phone_id" => $this->phone_id, 
				"_csrftoken" => $token[0], 
				"username" => $this->username, 
				"guid" => $this->uuid, 
				"device_id" => $this->device_id, 
				"password" => $this->password, 
				"login_attempt_count" => "0"
			);

			$login_response = $this->request("accounts/login/", $post, true);
			if( $login_response[1]["status"] == "fail" ){
				if(!isset($login_response[1]["challenge"])){

					if(isset($login_response[1]["message"]) && strpos($login_response[1]["message"], "checkpoint_required") !== FALSE){
						throw new Exception(lang("please_login_on_instagram_to_pass_checkpoint"));
					}

					throw new Exception($login_response[1]['message']);
				}

				$url = $login_response[1]["challenge"]["api_path"];
				set_session("ig_".$this->username."_checkpoint_url", $url);
				$checkpoint_response = $this->request_verification_code($url);
				throw new Exception(lang("enter_the_6_digit_code_we_sent_to_the_email_address").$checkpoint_response[1]['step_data']['contact_point']);
				return $checkpoint_response;
			}

			$this->isLoggedIn = true;
			$this->username_id = $login_response[1]["logged_in_user"]["pk"];
			$this->rank_token = $this->username_id . "_" . $this->uuid;
			preg_match("#Set-Cookie: csrftoken=([^;]+)#", $login_response[0], $match);if(!isset($match[1])) $match[1]='';
			set_session("ig_".$this->username."_csrftoken", $match[1]);

			if(isset($response[1]['logged_in_user'])){
				set_session("ig_".$this->username."_uid", $response[1]['logged_in_user']['pk']);
			}

			$this->token = $match[1];
			return $login_response;
		}else{
			if(strlen($this->security_code) != 6){
				throw new Exception(lang("security_code_incorrect"));
			}

			$response = $this->verification_code($this->security_code);

			if(isset($response[1]['message'])){
				throw new Exception($response[1]['message']);
			}

			preg_match("#Set-Cookie: csrftoken=([^;]+)#", $response[0], $match);if(!isset($match[1])) $match[1]='';
			set_session("ig_".$this->username."_csrftoken", $match[1]);
			if(isset($response[1]['logged_in_user'])){
				set_session("ig_".$this->username."_uid", $response[1]['logged_in_user']['pk']);
			}
			unset_session("ig_".$this->username."_checkpoint_url");
			
			return $response;
		}
	}

	public function request($endpoint, $post = NULL, $login = false){
		$ip = "78." . rand(160, 191) . "." . rand(1, 255) . "." . rand(1, 255);

		$headers = array(
			"Connection: close", "Accept: */*", 
			"X-IG-Capabilities: " . Constants::X_IG_Capabilities, 
			"X-IG-Connection-Type: WIFI", 
			"Content-type: application/x-www-form-urlencoded; charset=UTF-8", 
			"Accept-Language: en-US", 
			"X_FORWARDED_FOR: " . $ip, 
			"REMOTE_ADDR: " . $ip,
			'Connection: keep-alive',
            'Proxy-Connection: keep-alive', 
            'x-requested-with: keep-alive', 
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, Constants::API_URL . $endpoint);
		curl_setopt($ch, CURLOPT_USERAGENT, "Instagram 42.0.0.19.95 Android (23/6.0.1; 640dpi; 1440x2560; samsung; SM-G935F; hero2lte; samsungexynos8890)");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie_path);

        $proxy = $this->proxy;
        if ($proxy != "") {
            $proxyItem = explode("@", $proxy);
            if (count($proxyItem) > 1) {
                $oauth = explode("//",  $proxyItem[0]);
                if(count($oauth) > 1){
                    $oauth =  $oauth[1];
                }else{
                    $oauth =  $oauth[0];
                }   

                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $oauth);
                $host = explode(':', $proxyItem[1]);
                curl_setopt($ch, CURLOPT_PROXY, $host[0]);
                curl_setopt($ch, CURLOPT_PROXYPORT, $host[1]);
            } else {
                $host = explode(':', $proxy);
                curl_setopt($ch, CURLOPT_PROXY, $host[0]);
                curl_setopt($ch, CURLOPT_PROXYPORT, $host[1]);
            }
        }

		if($post){
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, SignatureUtils::generateSignature(json_encode($post)));
		}

		$resp = curl_exec($ch);
		$header_len = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($resp, 0, $header_len);
		$body = substr($resp, $header_len);
		curl_close($ch);

		if(1==2){
			echo "REQUEST: " . $endpoint;

			if(!is_null($post) && !is_array($post)){
				echo "DATA: " . urldecode($post);
			}

			echo "RESPONSE: " . $body;
		}

		return array( $header, json_decode($body, true) );
	}
}

class SignatureUtils
{
	public static function generateSignature($data)
	{
	$hash = hash_hmac("sha256", $data, Constants::IG_SIG_KEY);
	return "ig_sig_key_version=" . Constants::SIG_KEY_VERSION . "&signed_body=" . $hash . "." . urlencode($data);
	}
	public static function generateDeviceId($seed)
	{
	$volatile_seed = filemtime(__DIR__);
	return "android-" . substr(md5($seed . $volatile_seed), 16);
	}
	public static function generateUUID($type)
	{
	$uuid = sprintf("%04x%04x-%04x-%04x-%04x-%04x%04x%04x", mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 4095) | 16384, mt_rand(0, 16383) | 32768, mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
	return ($type ? $uuid : str_replace("-", "", $uuid));
	}
}

class Constants{
	const API_URL = "https://i.instagram.com/api/v1/";
	const VERSION = "10.3.2";
	const IG_SIG_KEY = "5ad7d6f013666cc93c88fc8af940348bd067b68f0dce3c85122a923f4f74b251";
	const EXPERIMENTS = "ig_android_ad_holdout_16m5_universe,ig_android_progressive_jpeg,ig_creation_growth_holdout,ig_android_oppo_app_badging,ig_android_ad_remove_username_from_caption_universe,ig_android_enable_share_to_whatsapp,ig_android_direct_drawing_in_quick_cam_universe,ig_android_ad_always_send_ad_attribution_id_universe,ig_android_universe_video_production,ig_android_direct_plus_button,ig_android_ads_heatmap_overlay_universe,ig_android_http_stack_experiment_2016,ig_android_infinite_scrolling,ig_fbns_blocked,ig_android_post_auto_retry_v7_21,ig_fbns_push,ig_android_video_playback_bandwidth_threshold,ig_android_direct_link_preview,ig_android_direct_typing_indicator,ig_android_preview_capture,ig_android_feed_pill,ig_android_profile_link_iab,ig_android_story_caption,ig_android_network_cancellation,ig_android_histogram_reporter,ig_android_anrwatchdog,ig_android_search_client_matching,ig_android_follow_request_text_buttons,ig_android_feed_zoom,ig_android_drafts_universe,ig_android_disable_comment,ig_android_user_detail_endpoint,ig_android_os_version_blocking,ig_android_blocked_list,ig_android_event_creation,ig_android_high_res_upload_2,ig_android_2fac,ig_android_mark_reel_seen_on_Swipe_forward,ig_android_comment_redesign,ig_android_ad_sponsored_label_universe,ig_android_mentions_dismiss_rule,ig_android_disable_chroma_subsampling,ig_android_share_spinner,ig_android_video_reuse_surface,ig_explore_v3_android_universe,ig_android_media_favorites,ig_android_nux_holdout,ig_android_insta_video_universe,ig_android_search_null_state,ig_android_universe_reel_video_production,liger_instagram_android_univ,ig_android_direct_emoji_picker,ig_feed_holdout_universe,ig_android_direct_send_auto_retry_universe,ig_android_samsung_app_badging,ig_android_disk_usage,ig_android_business_promotion,ig_android_direct_swipe_to_inbox,ig_android_feed_reshare_button_nux,ig_android_react_native_boost_post,ig_android_boomerang_feed_attribution,ig_fbns_shared,ig_fbns_dump_ids,ig_android_react_native_universe,ig_show_promote_button_in_feed,ig_android_ad_metadata_behavior_universe,ig_android_video_loopcount_int,ig_android_inline_gallery_backoff_hours_universe,ig_android_rendering_controls,ig_android_profile_photo_as_media,ig_android_async_stack_image_cache,ig_video_max_duration_qe_preuniverse,ig_video_copyright_whitelist,ig_android_render_stories_with_content_override,ig_android_ad_intent_to_highlight_universe,ig_android_swipe_navigation_x_angle_universe,ig_android_disable_comment_public_test,ig_android_profile,ig_android_direct_blue_tab,ig_android_enable_share_to_messenger,ig_android_fetch_reel_tray_on_resume_universe,ig_android_promote_again,ig_feed_event_landing_page_channel,ig_ranking_following,ig_android_pending_request_search_bar,ig_android_feed_ufi_redesign,ig_android_pending_edits_dialog_universe,ig_android_business_conversion_flow_universe,ig_android_show_your_story_when_empty_universe,ig_android_ad_drop_cookie_early,ig_android_app_start_config,ig_android_fix_ise_two_phase,ig_android_ppage_toggle_universe,ig_android_pbia_normal_weight_universe,ig_android_profanity_filter,ig_ios_su_activity_feed,ig_android_search,ig_android_boomerang_entry,ig_android_mute_story,ig_android_inline_gallery_universe,ig_android_ad_remove_one_tap_indicator_universe,ig_android_view_count_decouple_likes_universe,ig_android_contact_button_redesign_v2,ig_android_periodic_analytics_upload_v2,ig_android_send_direct_typing_indicator,ig_android_ad_holdout_16h2m1_universe,ig_android_react_native_comment_moderation_settings,ig_video_use_sve_universe,ig_android_inline_gallery_no_backoff_on_launch_universe,ig_android_immersive_viewer,ig_android_discover_people_icon,ig_android_profile_follow_back_button,is_android_feed_seen_state,ig_android_dense_feed_unit_cards,ig_android_drafts_video_universe,ig_android_exoplayer,ig_android_add_to_last_post,ig_android_ad_remove_cta_chevron_universe,ig_android_ad_comment_cta_universe,ig_android_search_event_icon,ig_android_channels_home,ig_android_feed,ig_android_dv2_realtime_private_share,ig_android_non_square_first,ig_android_video_interleaved_v2,ig_android_video_cache_policy,ig_android_react_native_universe_kill_switch,ig_android_video_captions_universe,ig_android_follow_search_bar,ig_android_last_edits,ig_android_two_step_capture_flow,ig_android_video_download_logging,ig_android_share_link_to_whatsapp,ig_android_facebook_twitter_profile_photos,ig_android_swipeable_filters_blacklist,ig_android_ad_pbia_profile_tap_universe,ig_android_use_software_layer_for_kc_drawing_universe,ig_android_react_native_ota,ig_android_direct_mutually_exclusive_experiment_universe,ig_android_following_follower_social_context";
	const LOGIN_EXPERIMENTS = "ig_android_reg_login_btn_active_state,ig_android_ci_opt_in_at_reg,ig_android_one_click_in_old_flow,ig_android_merge_fb_and_ci_friends_page,ig_android_non_fb_sso,ig_android_mandatory_full_name,ig_android_reg_enable_login_password_btn,ig_android_reg_phone_email_active_state,ig_android_analytics_data_loss,ig_fbns_blocked,ig_android_contact_point_triage,ig_android_reg_next_btn_active_state,ig_android_prefill_phone_number,ig_android_show_fb_social_context_in_nux,ig_android_one_tap_login_upsell,ig_fbns_push,ig_android_phoneid_sync_interval";
	const SIG_KEY_VERSION = "4";
	const X_IG_Capabilities = "3ToAAA==";
	const ANDROID_VERSION = 18;
	const ANDROID_RELEASE = "4.3";
	const WEB_URL = "https://www.instagram.com/";
	const WEB_USER_AGENT = "Mozilla/5.0 (Linux; Android 4.4.2; HUAWEI MT7-L09 Build/HuaweiMT7-L09) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.76 Mobile Safari/537.36";
}