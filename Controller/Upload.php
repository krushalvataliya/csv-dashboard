<?php 
require_once 'Controller/Core/Action.php';

class Controller_Upload extends Controller_Core_Action
{
	public function IndexAction()
	{	
		$this->getTemplete('upload/index.phtml');
	}

	public function exportAction()
	{
	    try {
	        $request = $this->getRequest();
	        if (!$request->isPost()) {
	            throw new Exception("Invalid Request.", 1);
	        }
	        if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] == UPLOAD_ERR_OK) {
	            $uploadDir = Kv::getCsvDir();
	            $originalFilename = pathinfo($_FILES['csvFile']['name'], PATHINFO_FILENAME);
	            $originalExtension = pathinfo($_FILES['csvFile']['name'], PATHINFO_EXTENSION);
	            $uploadFile = $uploadDir . $originalFilename . '.' . $originalExtension;

	            $counter = 1;
	            while (file_exists($uploadFile)) {
	                $uploadFile = $uploadDir . $originalFilename . '(' . $counter . ').' . $originalExtension;
	                $counter++;
	            }

	            if (!is_dir($uploadDir)) {
	                mkdir($uploadDir, 0755, true);
	            }
	            if (move_uploaded_file($_FILES['csvFile']['tmp_name'], $uploadFile)) {
	                $this->getMessage()->addMessage("File successfully uploaded: " . htmlspecialchars(basename($uploadFile)), Model_Core_Message::SUCCESS);
	            } else {
	                throw new Exception("Error uploading file.", 1);
	            }
	        } else {
	            throw new Exception("No file uploaded or there was an error uploading the file.", 1);
	        }
	    } catch (Exception $e) {
	        $this->getMessage()->addMessage('Error: ' . $e->getMessage(), Model_Core_Message::FAILURE);
	    }
	    return $this->redirect('index');
	}
}

?>