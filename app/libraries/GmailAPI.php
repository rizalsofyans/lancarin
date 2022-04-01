<?php
require APPPATH. '/vendor/autoload.php';

class GmailAPI{
	
	function send($from, $to, $subject, $message, $attachment=[]){
		if(!empty($attachment)){
			$attachment=$attachment[0];
		}
		
		$client = new Google_Client();
		$client->addScope("https://mail.google.com/");
		$client->setApplicationName("lancarin-email");
    	$client->setAuthConfig("google-cloud-credential.json");
		$client->setConfig('subject', 'support@lancarin.com');

		$service = new Google_Service_Gmail($client);
		$boundary = uniqid(rand(), true);
		$user = 'me';
		$strSubject = $subject;
		$strRawMessage = "From: $from\r\n";
		$strRawMessage .= "To: $to\r\n";
		$strRawMessage .= 'Subject: =?utf-8?B?' . base64_encode($strSubject) . "?=\r\n";
		$strRawMessage .= "MIME-Version: 1.0\r\n";
		if(empty($attachment)){
			$strRawMessage .= "Content-Type: text/html; charset=utf-8\r\n";
			$strRawMessage .= 'Content-Transfer-Encoding: base64' . "\r\n\r\n";
		}else{
			$strRawMessage .= 'Content-type: Multipart/Mixed; boundary="' . $boundary . '"' . "\r\n";
			$filePath = $attachment;
			$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
			$mimeType = finfo_file($finfo, $filePath);
			$fileName = basename($attachment);
			$fileData = base64_encode(file_get_contents($filePath));

			$strRawMessage .= "\r\n--{$boundary}\r\n";
			$strRawMessage .= 'Content-Type: '. $mimeType .'; name="'. $fileName .'";' . "\r\n";            
			//$strRawMessage .= 'Content-ID: <' . $strSesFromEmail . '>' . "\r\n";            
			$strRawMessage .= 'Content-ID: <' . $boundary . '>' . "\r\n";            
			$strRawMessage .= 'Content-Description: ' . $fileName . ';' . "\r\n";
			$strRawMessage .= 'Content-Disposition: attachment; filename="' . $fileName . '"; size=' . filesize($filePath). ';' . "\r\n";
			$strRawMessage .= 'Content-Transfer-Encoding: base64' . "\r\n\r\n";
			$strRawMessage .= chunk_split(base64_encode(file_get_contents($filePath)), 76, "\n") . "\r\n";
			$strRawMessage .= "--{$boundary}\r\n";
			$strRawMessage .= 'Content-Type: text/html; charset=utf-8' .  "\r\n";
			$strRawMessage .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n\r\n";

		}
		$strRawMessage .= $message. "\r\n";
		// The message needs to be encoded in Base64URL
		$mime = rtrim(strtr(base64_encode($strRawMessage), '+/', '-_'), '=');
		$msg = new Google_Service_Gmail_Message();
		$msg->setRaw($mime);
		$service->users_messages->send($user, $msg);
	}
}