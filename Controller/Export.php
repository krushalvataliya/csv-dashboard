<?php 
require_once 'Controller/Core/Action.php';
require_once 'Model/Export.php';

class Controller_Export extends Controller_Core_Action
{
	public function IndexAction()
	{	
		$this->getTemplete('export/index.phtml');
	}

	public function exportAction()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		    $orderId = (int)$_POST['orderId'];
		    $queryType = htmlspecialchars($_POST['queryType']);

		    $allData = [
		        ['file_name' => $queryType.'-'.$orderId.'-'.'sales_flat_order.csv', 'sql' => "SELECT * FROM `sales_flat_order` WHERE `entity_id` =".$orderId],
		        ['file_name' => $queryType.'-'.$orderId.'-'.'sales_flat_order_item.csv', 'sql' => "SELECT * FROM `sales_flat_order_item` WHERE `order_id` =".$orderId],
		        ['file_name' => $queryType.'-'.$orderId.'-'.'sales_flat_order_item_additional.csv', 'sql' => "SELECT * FROM `sales_flat_order_item_additional` WHERE `item_id` IN (SELECT item_id FROM `sales_flat_order_item` WHERE `order_id` = ".$orderId.")"],
		        ['file_name' => $queryType.'-'.$orderId.'-'.'ccc_manufacturer_order.csv', 'sql' => "SELECT * FROM `ccc_manufacturer_order` WHERE `order_id` = ".$orderId],
		        ['file_name' => $queryType.'-'.$orderId.'-'.'ccc_manufacturer_order_item_additional.csv', 'sql' => "SELECT * FROM `ccc_manufacturer_order_item_additional` WHERE `order_id` = ".$orderId],
		        ['file_name' => $queryType.'-'.$orderId.'-'.'ccc_manufacturer_order_item_part_data.csv', 'sql' => "SELECT * FROM `ccc_manufacturer_order_item_part_data` WHERE `order_id` = ".$orderId]
		    ];

		    $connection = $this->getAdapter();
		    foreach ($allData as $data) {
		        $sql = $data['sql'];
		        $results = $connection->fetchAll($sql);
		        $csvFile = './var/uploads/'.$data['file_name'];
		        if (file_exists($csvFile)) {
		            echo $csvFile . " already exists.";
		            continue;  
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
		    echo $queryType . " CSV export completed.";
		    return $this->redirect('index');
		}
	}
}

?>