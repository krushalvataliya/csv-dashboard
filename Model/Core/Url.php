<?php 
require_once "Model/Core/Request.php";

class Model_Core_Url
{
	
	public function getCurrentUrl()
	{
	return $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}


	public function getUrl($a=null,$c=null, array $perameters=null,$reset = false)
	{
		$request = new Model_Core_Request();
		$final = ['a'=>null,'c'=>null];
		if($reset == false){
			$final = array_merge($final,$request->getParam());
		}
		($a == null) ? ($final['a'] = $request->getActionName()) : ($final['a'] = $a);
		($c == null) ? ($final['c'] = $request->getControllerName()) : ($final['c'] = $c);
		if($a == null && $c == null && $perameters == null && $reset == false){
			return $this->getCurrentUrl();
		}

		if($perameters != null){
			$final = array_merge($final,$perameters);
		}
		$url = explode('?', $this->getCurrentUrl());
		return $url[0].'?'.http_build_query($final);
	}
}

?>