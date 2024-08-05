<?php 
require_once 'Controller/Core/Action.php';

class Controller_Download extends Controller_Core_Action
{
	public function IndexAction()
	{	
		$this->getTemplete('download/index.phtml');
	}

	public function FetchCsvAction()
	{
		$csvDir = Kv::getCsvDir();
		$files = array_filter(scandir($csvDir), function($file) use ($csvDir) {
		    return is_file($csvDir . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'csv';
		});
		$this->getResponse()->jsonResponse(array_values($files));
	}

	public function downloadAction()
	{
		try {
			$request = $this->getRequest();
			$csvDir = Kv::getCsvDir();
		    $file = trim($request->getParam('file'));
			$file = isset($file) ? basename($file) : '';
			$filePath = $csvDir . $file;
			if (file_exists($filePath)) {
			    header('Content-Type: text/csv');
			    header('Content-Disposition: attachment; filename="' . $file . '"');
			    readfile($filePath);
			    exit;
				$this->getMessage()->addMessage("file download successfully.",  Model_Core_Message::SUCCESS);
			} else {
				throw new Exception('File not found.', 1);
			}
		} catch (Exception $e) {
			$this->getMessage()->addMessage('Error: '.$e->getMessage(),  Model_Core_Message::FAILURE);
		}
		return $this->redirect('index');
	}

	public function deleteAction()
	{
		try {
			$request = $this->getRequest();
			$csvDir = Kv::getCsvDir();
		    $file = trim($request->getParam('file'));
			$file = isset($file) ? basename($file) : '';
			$filePath = $csvDir . $file;
			if (1 || in_array($_SERVER['REMOTE_ADDR'], ['10.0.0.80', '192.168.0.168'])) {
				if (file_exists($filePath)) {
				    unlink($filePath);
					$this->getMessage()->addMessage("File deleted successfully.",  Model_Core_Message::SUCCESS);
				} else {
					throw new Exception('File not found.', 1);
				} 
			} else {
				throw new Exception("You dont have access to delete the file", 1);
			}
		} catch (Exception $e) {
			$this->getMessage()->addMessage('Error: '.$e->getMessage(),  Model_Core_Message::FAILURE);
		}
	}
}

?>