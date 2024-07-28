<?php
error_reporting(E_ALL);
define("DS", DIRECTORY_SEPARATOR);
require_once "Controller/Core/Front.php";

class Kv
{
	
	public static function init()
	{
		$front = new Controller_Core_Front();
		$front->init();
	}

	public static function getCsvDir()
	{
		return '.'. DS .'var'. DS .'uploads'. DS;
	}
}

Kv::init();

?>