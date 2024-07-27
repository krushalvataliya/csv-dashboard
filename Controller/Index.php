<?php 
require_once 'Controller/Core/Action.php';
require_once 'Model/Product.php';

class Controller_Index extends Controller_Core_Action
{
	public function IndexAction()
	{	
		$this->getTemplete('index.phtml');
	}
}

?>