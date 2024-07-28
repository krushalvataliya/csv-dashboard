<?php 
require_once 'Controller/Core/Action.php';
require_once 'Model/Export.php';

class Controller_Export extends Controller_Core_Action
{
	protected $modelExport = null;

	public function IndexAction()
	{	
		$this->getTemplete('export/index.phtml');
	}

	public function exportAction()
	{
		try {
			$request = $this->getRequest();
			if (!$request->isPost()) {
				throw new Exception("invalid Request.", 1);
			}
		    $orderId = (int)$request->getPost('orderId');
		    $queryType = htmlspecialchars($request->getPost('queryType'));
		    $allData = $this->getExportModel()->setQueryType($queryType)->setOrderId($orderId)->getAllOrderQueries(); 
		    $connection = $this->getAdapter();
		    foreach ($allData as $data) {
		        $sql = $data['sql'];
		        $results = $connection->fetchAll($sql);
		        if(!$results){
					throw new Exception("orders data not found", 1);
				}
		        $csvFile = Kv::getCsvDir().$data['file_name'];
		        if (file_exists($csvFile)) {
		            throw new Exception($csvFile . " already exists.", 1);
		        }
		        $file = fopen($csvFile, 'w');
		        if (!empty($results)) {
		            $headerRow = array_keys($results[0]);
		            fputcsv($file, $headerRow);
		            foreach ($results as $row) {
		                fputcsv($file, $row);
		            }
		        }
		        fclose($file);
		    }
		    $this->getMessage()->addMessage($queryType . " CSV export completed.",  Model_Core_Message::SUCCESS);
		} catch (Exception $e) {
		    $this->getMessage()->addMessage('Error: '.$e->getMessage(),  Model_Core_Message::FAILURE);
		}
	    return $this->redirect('index');	
	}

	public function getExportModel()
    {
        if (!$this->modelExport) {
        $this->modelExport = new Model_Export();
        }
        return $this->modelExport;
    }
}

?>