<?php 
require_once 'Controller/Core/Action.php';

class Controller_Custom_Export extends Controller_Core_Action
{
	public function IndexAction()
	{	
		$this->getTemplete('custom/export/index.phtml');
	}

	public function exportAction()
	{
		try {
			$request = $this->getRequest();
			if (!$request->isPost()) {
				throw new Exception("invalid Request.", 1);
			}
		    $db = $request->getPost('database');
		    $sqlQuery = trim($request->getPost('sqlQuery'));
		    $fileName = trim($request->getPost('fileName'));

		    if (empty($sqlQuery)) {
				throw new Exception('SQL Query is required.', 1);
		    }
		    $timestamp = time();
		    if(file_exists($fileName)){
		        $fileName = $fileName.$timestamp;
		    }
		    $csvFile = "{$fileName}.csv";
	        $results = $this->getAdapter()->setdatabaseName($db)->fetchAll($sqlQuery);

	        if (empty($results)) {
				throw new Exception('No data found for the provided query.', 1);
	        }

	        $file = fopen($csvFile, 'w');
	        $headerRow = array_keys($results[0]);
	        fputcsv($file, $headerRow);

	        foreach ($results as $row) {
	            fputcsv($file, $row);
	        }
	        fclose($file);
			$this->getMessage()->addMessage("CSV export completed. <a href='{$csvFile}' download>Download CSV</a>",  Model_Core_Message::SUCCESS);
		} catch (Exception $e) {
			$this->getMessage()->addMessage('Error: '.$e->getMessage(),  Model_Core_Message::FAILURE);
		}
		 return $this->redirect('index');
	}

	public static function getExcludedDbs()
	{
		return ['information_schema', 'mysql', 'performance_schema', 'phpmyadmin'];
	}

	public function getAvailableDbs()
	{
		$result = []; 
		$dbs = $this->getAdapter()->fetchAll("SHOW DATABASES");
        if ($dbs) {
            foreach ($dbs as $db => $name) {
            	if (!in_array($name['Database'], self::getExcludedDbs())) {
	                $result[$name['Database']] = $name['Database'];
            	}
            }
        } 
        return $result;
	}
}

?>