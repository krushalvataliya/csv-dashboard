<?php 
require_once 'Controller/Core/Action.php';
require_once 'Model/Export.php';

class Controller_Download extends Controller_Core_Action
{
	public function IndexAction()
	{	
		$this->getTemplete('download/index.phtml');
	}

	public static function getCsvDir()
	{
		return './var/uploads/';
	}

	public function FetchCsvAction()
	{
		$csvDir = self::getCsvDir();
		$files = array_filter(scandir($csvDir), function($file) use ($csvDir) {
		    return is_file($csvDir . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'csv';
		});
		echo json_encode(array_values($files));
	}

	public function downloadAction()
	{
		$csvDir = self::getCsvDir();
		$file = isset($_GET['file']) ? basename($_GET['file']) : '';
		$filePath = $csvDir . $file;

		if (file_exists($filePath)) {
		    header('Content-Type: text/csv');
		    header('Content-Disposition: attachment; filename="' . $file . '"');
		    readfile($filePath);
		    exit;
		} else {
		    echo 'File not found.';
		}
		return $this->redirect('index');
	}

	public function deleteAction()
	{
		$csvDir = self::getCsvDir();
		$file = isset($_GET['file']) ? basename($_GET['file']) : '';
		$filePath = $csvDir . $file;
		if (1 || in_array($_SERVER['REMOTE_ADDR'], ['10.0.0.80', '192.168.0.168'])) {
			if (file_exists($filePath)) {
			    unlink($filePath);
			    echo 'File deleted successfully.';
			} else {
			    echo 'File not found.';
			} 
		} else {
		    echo 'You dont have access to delete the file';
		}
	}
}

?>