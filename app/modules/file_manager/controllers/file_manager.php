<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class file_manager extends MX_Controller {
	public $max_size = 5*1024;
	public $info_upload;
	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
		$info_upload = $this->model->get_storage();
		$this->info_upload = $info_upload;
		$this->max_size = $info_upload->max_file_size*1024;
	}

	public function index(){
		$data = array(
			"info"       => $this->info_upload,
			"total_item" => $this->model->getFiles(-1, -1),
		);
		$this->template->build('index', $data);

		if(!permission("photo_type") && !permission("video_type")){
			redirect(cn("dashboard"));	
		}
	}
	
	public function image_editor(){
		
		$data = array(
			
		);

		$this->template->build('image_editor', $data);
	}

	public function image_editor_popup(){
		
		$data = array(
			
		);

		$this->template->build('image_editor_popup', $data);
	}

	public function popup_filemanager(){
		$data = array(
			"id" => get("id")
		);
		$this->load->view('popup_file_manual_activity', $data);
	}

	
	public function block_file_manager($type = "single", $max_choice = 1){
		$this->load->view("block_file_manager", array("type" => $type, "max_choice" => $max_choice));
	}

	public function upload_base64(){
		$token_name= $this->security->get_csrf_token_name();
		if(empty(post())){
			$req = json_decode(file_get_contents('php://input'),true); 
			$ids = isset($req['ids'])?$req['ids']:null;
			$token = isset($req[$token_name])?$req[$token_name]:null;
			$data = isset($req['data'])?$req['data']:null;
		}else{
			$ids = post('ids');
			$token = post($token_name);
			$data = $this->input->post('data',false);
		}
		
		$uid = session('uid');
		$hash = $this->security->get_csrf_hash();

		if($token != $hash){
			ms([
				'status'=>'error',
				'message' => 'CSRF failed'
			]);
		}

		$q = $this->db->get_where(FILE_MANAGER, ['ids'=>$ids, 'uid'=>$uid]);
		if(!empty($ids) && $q->num_rows() != 1){
			ms([
				'status'=>'error',
				'message' => 'File not found'
			]);
		}elseif(!empty($ids) && $q->num_rows() == 1){
			$filename = $q->row()->file_name;
			$file_ext = $q->row()->file_ext;
		}else{
			$file_ext = 'png';
			$filename = md5($data . microtime()).'.'.$file_ext;
		}

		$folder = 'assets/uploads/user'.$uid;

		$exp = explode(',', $data);
		$imageData = base64_decode($exp[1]); 
		$filesize = round(strlen($imageData)/1024, 2);
		$mime_type = getimagesizefromstring($imageData);
		if(!isset($mime_type['mime']) || $mime_type['mime'] !='image/png'){//expect input always png then convert to desired format later
			ms([
				'status'=>'error',
				'message' => 'Format is not allowed'
			]);
		}
		$source = imagecreatefromstring($imageData);

		get_upload_folder();
		$config['upload_path'] = './assets/uploads/user'.session("uid");
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size'] = $this->max_size;
        $config['max_width'] = 0;
        $config['max_height'] = 0;
        if(!empty($ids)){
        	$config['file_name'] = $filename;
        }else{
        	$config['encrypt_name'] = TRUE;
	        $config['file_ext_tolower'] = true;
        }
        
        $this->load->library('upload', $config);
		$this->model->get_storage("file", $filesize);
        $this->upload->initialize($config);

        if($file_ext=='png'){
			$imageSave = imagepng($source,$folder.'/'.$filename, get_option('image_editor_png_quality', 0));
		}else{
			$imageSave = imagejpeg($source,$folder.'/'.$filename,get_option('image_editor_jpg_quality', 100));
		}
		imagedestroy($source);

        if (!$imageSave){
            ms(array(
            	"status"  => "error",
            	"message" => 'Upload file failed'
            ));
        }else{
        	$filesize = round(filesize($folder.'/'.$filename)/1024,2);
        	if(!empty($ids)){
        		$data = array(
	        		"file_size" => $filesize,
	        		"image_width" => (int)$mime_type[0],
	        		"image_height" => (int)$mime_type[1],
	        	);

	        	$this->db->update(FILE_MANAGER, $data, ['uid'=>session("uid"), 'ids'=>$ids]);
        	}else{
        		$data = array(
	        		"ids" => ids(),
	        		"uid" => session("uid"),
	        		"file_name" => $filename,
	        		"image_type" => $mime_type['mime'],
	        		"file_ext" => $file_ext,
	        		"file_size" => $filesize,
	        		"is_image" => 1,
	        		"image_width" => (int)$mime_type[0],
	        		"image_height" => (int)$mime_type[1],
	        		"created" => NOW,
	        	);

	        	$this->db->insert(FILE_MANAGER, $data);
        	}
        	
            ms(array(
            	"status"  => "success",
            	"message" => 'Upload file success',
            	"link"    => get_link_file($filename)
            ));
        }
	}
	
	public function ajax_load_files(){
		$limit = 16;
		$page = (int)post("page");
		$total_item = $this->model->getFiles(-1, -1);

		$data = array(
			"page"       => $page + 1,
			"limit"      => $limit,
			"total_item" => $total_item,
			"files"      => $this->model->getFiles($limit, $page)
		);

		ms(array(
			"total_item" => $total_item,
			"data"       => $this->load->view('ajax_load_files', $data, 1)
		));
	}

	public function popup_add_files(){
		$data = array(
			"id" => get("id")
		);
		$this->load->view('popup_add_files', $data);
	}

	public function upload_files(){
		get_upload_folder();

		$types = "";
		if(permission("photo_type") && permission("video_type")){
			$types = 'gif|jpg|jpeg|png|mp4';
		}else if(permission("photo_type")){
			$types = 'gif|jpg|jpeg|png';
		}else if(permission("video_type")){
			$types = 'mp4';
		}

		$config['upload_path']          = './assets/uploads/user'.session("uid");
        $config['allowed_types']        = $types;
        $config['max_size']             = $this->max_size;
        $config['max_width']            = 0;
        $config['max_height']           = 0;
        $config['encrypt_name']         = TRUE;
		$config['file_ext_tolower'] = true;

        $this->load->library('upload', $config);
        
        if(!empty($_FILES)){
	        $files = $_FILES;
		    for($i=0; $i< count($_FILES['files']['name']); $i++){  
		        $_FILES['files']['name']= $files['files']['name'][$i];
		        $_FILES['files']['type']= $files['files']['type'][$i];
		        $_FILES['files']['tmp_name']= $files['files']['tmp_name'][$i];
		        $_FILES['files']['error']= $files['files']['error'][$i];
		        $_FILES['files']['size']= $files['files']['size'][$i];
		        
		        $this->model->get_storage("file", $_FILES['files']['size']/1024);
		        $this->upload->initialize($config);

		        if (!$this->upload->do_upload("files"))
		        {
	                ms(array(
	                	"status"  => "error",
	                	"message" => $this->upload->display_errors()
	                ));
		        }
		        else
		        {
		        	$info = (object)$this->upload->data();
					$ids = ids();
		        	$data = array(
		        		"ids" => $ids,
		        		"uid" => session("uid"),
		        		"file_name" => $info->file_name,
		        		"image_type" => $files['files']['type'][$i],
		        		"file_ext" => str_replace(".", "", strtolower($info->file_ext)),
		        		"file_size" => $info->file_size,
		        		"is_image" => $info->is_image,
		        		"image_width" => (int)$info->image_width,
		        		"image_height" => (int)$info->image_height,
		        		"created" => NOW,
		        	);

		        	$this->db->insert(FILE_MANAGER, $data);

	                ms(array(
	                	"status"  => "success",
	                	"ids"  => $ids,
	                	"link"    => get_link_file($info->file_name)
	                ));
		        }
		    }
        }else{
        	load_404();
        }
	}

	function save_image(){
		get_upload_folder();

		if(get("image") || post("image")){
			$return_type = "page";
			$instagram_account = $this->model->get("*", 'instagram_accounts', "uid = '".session("uid")."' AND status = 1", "rand()");
			$proxy=null;
			$post_url=post('post_url');
			if(!empty($instagram_account)){
				$proxy = $instagram_account->proxy;
			}
			
			$ids = ids();
			if(get("image")){
				$return_type = "page";
				$media_link  = get("image");
				$media_link2 = $media_link;
				$fileParts   = pathinfo($media_link);
				$file_name   = $fileParts['basename'];
				$file_type   = get_file_type($file_name);
				$newfilename = md5(encrypt_encode($file_name)).".".$file_type;
			}

			if(post("image")){
				$return_type = "json";
				$media_link  = post("image");
				$media_ext  = post("ext");
				$exp = explode('?', $media_link);
				$media_link2 = $exp[0];
				$fileParts   = pathinfo($media_link2);
				if(!empty($media_ext)) $fileParts['extension']=$media_ext; 
				$file_name   = $ids.".".$fileParts['extension'];
				$file_type   = get_file_type($file_name);
				$newfilename = md5(encrypt_encode($file_name)).".".$file_type;
			}

			//if(!check_media($media_link, $media_ext)){
			if(!check_media($media_link2, $media_ext)){
				ms(array(
					"status"  => "error",
					"message" => "The filetype you are attempting to upload is not allowed!.",
				));
			}

			$file_size = curl_get_file_size($media_link, $post_url, $proxy);
			if($file_size > $this->max_size){
				ms(array(
					"status"  => "error",
					"message" => lang("you_have_exceeded_the_file_limit"),
				));
			}

			get_file_via_curl($media_link, $newfilename, $post_url, $proxy);

			$mime = mime_content_type(get_path_upload($newfilename));
			if(!strstr($mime, "video/") && !strstr($mime, "image/")){
				ms(array(
                	"status"  => "error",
                	"message" => "The filetype you are attempting to upload is not allowed.",
                ));
				unlink(get_path_upload($newfilename));
			}

			$file_size = @filesize(get_path_upload($newfilename));
			if(is_int($file_size) && $file_size/1024 > $this->max_size){
				ms(array(
                	"status"  => "error",
                	"message" => lang("you_have_exceeded_the_file_limit"),
                ));
                unlink(get_path_upload($newfilename));
			}

			$this->model->get_storage("file", $file_size/1024);

			$image_width = 0;
			$image_height = 0;
			$fileinfo = @getimagesize(get_path_upload($newfilename));
			if(!empty($fileinfo)){
				$image_width = $fileinfo[0];
				$image_height = $fileinfo[1];
			}
			
			$data = array(
				"ids"       => $ids,
        		"file_name" => $newfilename,
        		"image_type"=> $mime,
        		"file_ext"  => $file_type,
				"is_image"  => ($file_type == "jpeg" || $file_type == "jpg" || $file_type == "png"  || $file_type == "gif")?1:0,
				"file_size" => round($file_size/1024,2),
				"image_width" => $image_width,
        		"image_height" => $image_height,
         		"created"   => NOW
        	);

			//$media = $this->model->get("ids", FILE_MANAGER, "file_name = '".$newfilename."' AND uid = '".session("uid")."'");
			$media = $this->db->select('ids')->get_where(FILE_MANAGER, ["file_name"=>$newfilename, 'uid'=> session("uid")]); 
			//if(empty($media)){
			if($media->num_rows()==0){
				$data['uid']      = session("uid");
				$this->db->insert(FILE_MANAGER, $data);
			}else{
				$this->db->update(FILE_MANAGER, $data, "ids = '".$media->ids."'");
			}

        	if($return_type == "page"){
        		$this->template->build('index');
        	}else{
        		ms(array(
                	"status"  => "success",
                	"message" => "Upload successfully",
                	"link"    => get_link_file($newfilename)
                ));
        	}
		}else{
			load_404();
		}
	}
	
	public function save_image2(){
		get_upload_folder();

		if(get("image") || post("image")){
			$return_type = "page";

			if(get("image")){
				$return_type = "page";
				$media_link  = get("image");
				$fileParts   = pathinfo($media_link);
				$file_name   = $fileParts['basename'];
				$file_type   = get_file_type($file_name);
				$newfilename = md5(encrypt_encode($file_name)).".".$file_type;
			}

			if(post("image")){
				$return_type = "json";
				$media_link  = post("image");
				$media_ext  = post("ext");
				$exp = explode('?', $media_link);
				$media_link2 = $exp[0];
				$fileParts   = pathinfo($media_link2);
				if(!empty($media_ext)) $fileParts['extension']=$media_ext; 
				$file_name   = ids().".".$fileParts['extension'];
				$file_type   = get_file_type($file_name);
				$newfilename = md5(encrypt_encode($file_name)).".".$file_type;
			}

			//if(!check_media($media_link, $media_ext)){
			if(!check_media($media_link2, $media_ext)){
				ms(array(
					"status"  => "error",
					"message" => "The filetype you are attempting to upload is not allowed!.",
				));
			}

			$file_size = curl_get_file_size($media_link);
			if($file_size > $this->max_size){
				ms(array(
					"status"  => "error",
					"message" => lang("you_have_exceeded_the_file_limit"),
				));
			}

			get_file_via_curl($media_link, $newfilename);

			$mime = mime_content_type(get_path_upload($newfilename));
			if(!strstr($mime, "video/") && !strstr($mime, "image/")){
				ms(array(
                	"status"  => "error",
                	"message" => "The filetype you are attempting to upload is not allowed.",
                ));
				unlink(get_path_upload($newfilename));
			}

			$file_size = @filesize(get_path_upload($newfilename));
			if(is_int($file_size) && $file_size/1024 > $this->max_size){
				ms(array(
                	"status"  => "error",
                	"message" => lang("you_have_exceeded_the_file_limit"),
                ));
                unlink(get_path_upload($newfilename));

			}

			$this->model->get_storage("file", $file_size/1024);

			$image_width = 0;
			$image_height = 0;
			$fileinfo = @getimagesize(get_path_upload($newfilename));
			if(!empty($fileinfo)){
				$image_width = $fileinfo[0];
				$image_height = $fileinfo[1];
			}
			
			$data = array(
				"ids"       => ids(),
        		"file_name" => $newfilename,
        		"image_type"=> get_mime_type(get_file_type($newfilename)),
        		"file_ext"  => get_file_type($newfilename),
				"is_image"  => check_image($newfilename),
				"file_size" => round($file_size/1024,2),
				"image_width" => $image_width,
        		"image_height" => $image_height,
         		"created"   => NOW
        	);

			//$media = $this->model->get("ids", FILE_MANAGER, "file_name = '".$newfilename."' AND uid = '".session("uid")."'");
			$media = $this->db->select('ids')->get_where(FILE_MANAGER, ["file_name"=>$newfilename, 'uid'=> session("uid")]); 
			//if(empty($media)){
			if($media->num_rows()==0){
				$data['uid']      = session("uid");
				$this->db->insert(FILE_MANAGER, $data);
			}else{
				$this->db->update(FILE_MANAGER, $data, "ids = '".$media->ids."'");
			}

        	if($return_type == "page"){
        		$this->template->build('index');
        	}else{
        		ms(array(
                	"status"  => "success",
                	"message" => "Upload successfully",
                	"link"    => get_link_file($newfilename)
                ));
        	}
		}else{
			load_404();
		}
	}

	public function save_image_google_drive(){
		get_upload_folder();

		if(post("file_name") && post("file_id") && post("oauthToken") && permission("google_drive")){
			$fileId     = post("file_id");
			$file_name  = post("file_name");
			$file_size  = (int)post("file_size")/1024;
			$file_type  = get_file_type($file_name);
			$oAuthToken = post("oauthToken");
			$newfilename = md5(encrypt_encode($file_name)).".".$file_type;

			if(!check_media($file_name)){
				ms(array(
                	"status"  => "error",
                	"message" => "The filetype you are attempting to upload is not allowed.",
                ));
			}

			if(!$file_size || $file_size <= 0){
				ms(array(
                	"status"  => "error",
                	"message" => "Cannt load file size. Please try to again.",
                ));
			}

			$this->model->get_storage("file", $file_size);

			$getUrl = 'https://www.googleapis.com/drive/v3/files/' . $fileId . '?alt=media';
			$authHeader = 'Authorization: Bearer ' . $oAuthToken ;

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $getUrl);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_HEADER, 0);  
			curl_setopt($ch, CURLOPT_HTTPHEADER, [
			    $authHeader ,
			]);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

			$data = curl_exec($ch);
			$error = curl_error($ch);
			curl_close($ch);

			file_put_contents(get_path_upload($newfilename), $data);

			$mime = mime_content_type(get_path_upload($newfilename));
			if(!strstr($mime, "video/") && !strstr($mime, "image/")){
				ms(array(
                	"status"  => "error",
                	"message" => "The filetype you are attempting to upload is not allowed.",
                ));
				unlink(get_path_upload($newfilename));
			}

			$file_size = @filesize(get_path_upload($newfilename));
			if(is_int($file_size) && $file_size/1024 > $this->max_size){
				ms(array(
                	"status"  => "error",
                	"message" => lang("you_have_exceeded_the_file_limit"),
                ));
                unlink(get_path_upload($newfilename));
			}

			$image_width = 0;
			$image_height = 0;
			$fileinfo = @getimagesize(get_path_upload($newfilename));
			if(!empty($fileinfo)){
				$image_width = $fileinfo[0];
				$image_height = $fileinfo[1];
			}

			$data = array(
				"ids"       => ids(),
        		"file_name" => $newfilename,
        		"image_type"=> get_mime_type(get_file_type($newfilename)),
        		"file_ext"  => get_file_type($newfilename),
				"is_image"  => check_image($newfilename),
				"file_size" => round($file_size/1024, 2),
				"image_width" => $image_width,
        		"image_height" => $image_height,
         		"created"   => NOW
        	);
			
			$image = $this->model->get("ids", FILE_MANAGER, "file_name = '".$newfilename."' AND uid = '".session("uid")."'");
			
			if(empty($image)){
				$data['uid']      = session("uid");
				$this->db->insert(FILE_MANAGER, $data);
			}else{
				$this->db->update(FILE_MANAGER, $data, "ids = '".$image->ids."'");
			}

			ms(array(
	        	"status"  => "success",
	        	"link"    => get_link_file($newfilename)
	        ));
		}else{
			load_404();
		}
	}

	public function view_video(){
		$this->load->view("view_video", array("video" => get("video")));
	}

	public function delete_file(){
		$id = post("id");
		$image = $this->model->get("ids,file_name", FILE_MANAGER, "ids = '".$id."' AND uid = '".session("uid")."'");
		if(!empty($image)){
			unlink(APPPATH."../assets/uploads/user".session("uid")."/".$image->file_name);
			$this->db->delete(FILE_MANAGER, "ids = '".$id."'");
			ms(array(
				"status"  => "success",
				"message" => lang("delete_file_successfully")
			));
		}else{
			ms(array(
				"status"  => "error",
				"message" => lang("delete_file_failure")
			));
		}
	}

	public function delete_files(){
		$list_id = $this->input->post("id");

		foreach ($list_id as $id) {
			$image = $this->model->get("ids,file_name", FILE_MANAGER, "ids = '".$id."' AND uid = '".session("uid")."'");

			if(!empty($image)){
				unlink(APPPATH."../assets/uploads/user".session("uid")."/".$image->file_name);
				$this->db->delete(FILE_MANAGER, "ids = '".$id."'");
			}
		}

		ms(array(
			"status"  => "success",
			"message" => lang("delete_file_successfully")
		));
	}
}