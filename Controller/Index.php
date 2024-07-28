<?php 
require_once 'Controller/Core/Action.php';

class Controller_Index extends Controller_Core_Action
{
	public function IndexAction()
	{	
		$this->getTemplete('index.phtml');
	}
}

?>