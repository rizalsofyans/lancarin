<?php
require 'Bri.php';
class Bri_lib
{
    public function get_transactions($username, $password, $norek, $day=7)
    {
		$bri = new BRI;
		$res= $bri->set_credential($username, $password, $norek)->set_day($day)->set_type('kredit')->check_mutasi()->respond();
		return $res;
	}
}