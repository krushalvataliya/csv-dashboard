<?php 
require_once 'Controller/Core/Action.php';
require_once 'Model/Export.php';

class Controller_Upload extends Controller_Core_Action
{
	public function IndexAction()
	{	
		$this->getTemplete('upload/index.phtml');
	}

	public static function getCsvDir()
	{
		return './var/uploads/';
	}

	public function exportAction()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		    if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] == UPLOAD_ERR_OK) {
		        $uploadDir = self::getCsvDir();
		        $uploadFile = $uploadDir . basename($_FILES['csvFile']['name']);

		        if (!is_dir($uploadDir)) {
		            mkdir($uploadDir, 0755, true);
		        }
		        if (move_uploaded_file($_FILES['csvFile']['tmp_name'], $uploadFile)) {
		            echo "File successfully uploaded: " . htmlspecialchars(basename($_FILES['csvFile']['name']));
		        } else {
		            echo "Error uploading file.";
		        }
		    } else {
		        echo "No file uploaded or there was an error uploading the file.";
		    }
		}
		return $this->redirect('index');
	}
}

?>