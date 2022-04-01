<?php
if (!function_exists('get_path_upload')) {
	function get_path_upload($file_name = ""){
		return APPPATH."../assets/uploads/user".session("uid")."/".$file_name;
		//return FCPATH ."assets/uploads/user".session("uid")."/".$file_name; //ini yg bener tapi takut ada file lain yg manggil 
	};
}

if (!function_exists('get_path_file')) {
	function get_path_file($url = ""){
		//return str_replace(BASE, "", $url);
		return str_replace(parse_url($url, PHP_URL_SCHEME)."://".parse_url($url, PHP_URL_HOST)."/", "", $url);
	};
}

if (!function_exists('get_link_file')) {
	function get_link_file($file_name){
		$base = is_cli()?HOST_CLI:BASE;
		return $base."assets/uploads/user".session("uid")."/".$file_name;
	};
}

if (!function_exists('get_link_tmp')) {
	function get_link_tmp($file_name){
		return BASE.$file_name;
	};
}

if (!function_exists('get_file_type')) {
	function get_file_type($file_name){
		$file_name_array = explode(".", $file_name);
		return strtolower(end($file_name_array));
	};
}

if (!function_exists('get_image_size')) {
	function get_image_size($file_name){
		return @getimagesize($file_name);
	};
}

if (!function_exists('get_file_info')) {
	function get_file_info($file_name){
		$file_name = get_path_file($file_name);
		return @pathinfo($file_name);
	};
}

if (!function_exists('check_image')) {
	function check_image($file_name){
		$file_parts = pathinfo($file_name);

		if(!isset($file_parts['extension'])){
			return 0;
		}

		$extension = strtolower($file_parts['extension']);

		if($extension == "jpeg" || $extension == "jpg" || $extension == "png"  || $extension == "gif"){
			return 1;
		}else{
			return 0;
		}
	};
}

if (!function_exists('is_image')){
	function is_image($url){
		$data = @getimagesize($url);
		if(is_array($data)){
			return true;
		}else{
			return false;
		}
	}
}

if (!function_exists('check_media')) {
	function check_media($file_name, $ext=''){
		if(empty(session('uid')) && is_cli()){
			$photo_permission = permission("photo_type",1);
			$video_permission = permission("photo_type",1);
		}else{
			$photo_permission = permission("photo_type");
			$video_permission = permission("photo_type");
		}
		$file_parts = pathinfo($file_name); 
		if(!empty($ext)) $file_parts['extension']='jpg';
		$extension = strtolower($file_parts['extension']);
		if($photo_permission && $video_permission){
			if($extension == "jpeg" || $extension == "jpg" || $extension == "png"  || $extension == "gif" || $extension == "mp4"){
				return 1;
			}else{
				return 0;
			}
		}else if($photo_permission){
			if($extension == "jpeg" || $extension == "jpg" || $extension == "png"  || $extension == "gif"){
				return 1;
			}else{
				return 0;
			}
		}else if($video_permission){
			if($extension == "mp4"){
				return 1;
			}else{
				return 0;
			}
		}

		return 0;
	};
}

if (!function_exists('get_mime_type')) {
	function get_mime_type($file_name)
	{
	    $mime_types = array(
			"gif"  =>"image/gif",
	        "png"  =>"image/png",
	        "jpeg" =>"image/jpg",
	        "jpg"  =>"image/jpg",
	        "mp4"  =>"video/mp4",
	    );
	    $file_name_array = explode(".", $file_name);
	    $extension = strtolower(end($file_name_array));
	    return $mime_types[$extension];
	}
}

if (!function_exists('get_tmp_path')){
	function get_tmp_path($file = ""){
		return "assets/tmp/".$file;
	}
}

if (!function_exists('get_file_via_curl')){
	function get_file_via_curl($url, $file_name, $referer=null, $proxy=null){
		$ch = curl_init($url);
		$fp = fopen(APPPATH.'../assets/uploads/user'.session("uid").'/'.$file_name, 'wb');
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5000); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 5000);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		if(!empty($referer)) curl_setopt( $ch, CURLOPT_REFERER, $referer );
	  if(!empty($proxy)){
		$parse = parse_url($proxy);
		curl_setopt($ch, CURLOPT_PROXY, $parse['host'].":".$parse['port']);
		curl_setopt($ch, CURLOPT_PROXYUSERPWD, $parse['user'].":".$parse['pass']);
	  }
//		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0');

		curl_exec($ch);
		$curl_errno = curl_errno($ch);
		$curl_error = curl_error($ch);
		curl_close($ch);
		fclose($fp);
		if ($curl_errno > 0) {
			ms(['status'=>'error',
			'message'=>"cURL Error ($curl_errno): $curl_error\n"]);
		}
		
	}
}

if (!function_exists('curl_get_file_size')){
	function curl_get_file_size($url, $referer=null, $proxy=null){
	  // Assume failure.
	  $result = -1;

	  $curl = curl_init( $url );

	  // Issue a HEAD request and follow any redirects.
	  curl_setopt( $curl, CURLOPT_NOBODY, true );
	  curl_setopt( $curl, CURLOPT_HEADER, true );
	  curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	  if(!empty($referer)) curl_setopt( $curl, CURLOPT_REFERER, $referer );
	  if(!empty($proxy)){
		$parse = parse_url($proxy);
		curl_setopt($curl, CURLOPT_PROXY, $parse['host'].":".$parse['port']);
		curl_setopt($curl, CURLOPT_PROXYUSERPWD, $parse['user'].":".$parse['pass']);
	  }
	  curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
	  curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false);
	  curl_setopt( $curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0');
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5000); 
		curl_setopt($curl, CURLOPT_TIMEOUT, 5000);
		
	  $data = curl_exec( $curl );
	  $curl_errno = curl_errno($curl);
		$curl_error = curl_error($curl);
		
	  curl_close( $curl );
		if ($curl_errno > 0) {
			ms([
				'status'=>'error',
				'message'=>"cURL Error ($curl_errno): $curl_error\n"
			]);
		}
	  if( $data ) {
	    $content_length = 0;
	    $status = "unknown";

	    if( preg_match( "/^HTTP\/1\.[01] (\d\d\d)/", $data, $matches ) ) {
	      $status = (int)$matches[1];
	    }

	    if( preg_match( "/Content-Length: (\d+)/", $data, $matches ) ) {
	      $content_length = (int)$matches[1];
	    }
		
	    // http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
	    if( $status == 200 || ($status > 300 && $status <= 308) ) {
			$result = $content_length/(1024);
	    }
	  }

	  return $result;
	}
}
?>