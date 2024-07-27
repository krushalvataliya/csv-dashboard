<?php
require_once "Model/Core/Request.php";
require_once "Controller/Core/Action.php";
class Controller_Core_Front
{
	protected $request = null;

	public function getRequest()
	{
		if($this->request)
		{
			return $this->request;
		}
		$request = new Model_Core_Request();
		$this->setRequest($request);
		return $request;
	}

    protected function setRequest(Model_Core_Request $request)
	{
		$this->request = $request;
		return $this;
	}
	
	public function init()
	{
		$request = $this->getRequest();
		$controllerName =$request->getControllerName();
		if(!$controllerName)
		{
			throw new Exception("Controller does not exists in url.", 1);
		}
		
		$controllerClassName ='Controller_'.ucwords($controllerName, '_');
		$controllerClassPath = str_replace('_', '/', $controllerName);
		require_once 'Controller/'.$controllerClassPath.'.php';
		$action = $request->getActionName().'Action';
		$Controller = new $controllerClassName();
		if(!method_exists($Controller, $action))
		{
			$Controller->errorAction($action);
		}

		$Controller->$action();
	}

}
?>	