<?php 
require_once "Model/Core/Message.php";
$messageModel = new Model_Core_Message();
$messages = $messageModel->getMessages();

if($messages)
{
	foreach ($messages as $type => $message)
	{
		echo "<P class='{$type}'>";
		echo $message;
		echo "</P>";
	}
}
$messageModel->clearMessages();

?>