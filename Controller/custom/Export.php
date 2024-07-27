<?php 
require_once 'Controller/Core/Action.php';
require_once 'Model/Export.php';

class Controller_Custom_Export extends Controller_Core_Action
{
	public function IndexAction()
	{	
		$this->getTemplete('custom/export/index.phtml');
	}

	public function exportAction()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		    $db = trim($_POST['database']);
		    $sqlQuery = trim($_POST['sqlQuery']);
		    $fileName = trim($_POST['fileName']);

		    if (empty($sqlQuery)) {
		        die('SQL Query is required.');
		    }

		    $timestamp = time();
		    if(file_exists($fileName)){
		        $fileName = $fileName."new";
		    }
		    $csvFile = "{$fileName}.csv";
		    try {
		    	echo $sqlQuery;
		        $results = $this->getAdapter()->setdatabaseName($db)->fetchAll($sqlQuery);

		        if (empty($results)) {
		            die('No data found for the provided query.');
		        }

		        $file = fopen($csvFile, 'w');

		        $headerRow = array_keys($results[0]);
		        fputcsv($file, $headerRow);

		        foreach ($results as $row) {
		            fputcsv($file, $row);
		        }
		        fclose($file);
		        echo "CSV export completed. <a href='{$csvFile}' download>Download CSV</a>";
		    } catch (Exception $e) {
		        die("Error: " . $e->getMessage());
		    }
		}
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