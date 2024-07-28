<?php 
require_once "Model/Core/Main.php";

class Model_Export extends Model_Core_Main
{
	public function __construct(){
		Parent::__construct();
	}

	public function getAllOrderQueries()
	{
		$queryType = $this->getQueryType();
		$orderId = $this->getOrderId();
		return [
	        ['file_name' => $queryType.'-'.$orderId.'-'.'sales_flat_order.csv', 'sql' => "SELECT * FROM `sales_flat_order` WHERE `entity_id` =".$orderId],
	        ['file_name' => $queryType.'-'.$orderId.'-'.'sales_flat_order_item.csv', 'sql' => "SELECT * FROM `sales_flat_order_item` WHERE `order_id` =".$orderId],
	        ['file_name' => $queryType.'-'.$orderId.'-'.'sales_flat_order_item_additional.csv', 'sql' => "SELECT * FROM `sales_flat_order_item_additional` WHERE `item_id` IN (SELECT item_id FROM `sales_flat_order_item` WHERE `order_id` = ".$orderId.")"],
	        ['file_name' => $queryType.'-'.$orderId.'-'.'ccc_manufacturer_order.csv', 'sql' => "SELECT * FROM `ccc_manufacturer_order` WHERE `order_id` = ".$orderId],
	        ['file_name' => $queryType.'-'.$orderId.'-'.'ccc_manufacturer_order_item_additional.csv', 'sql' => "SELECT * FROM `ccc_manufacturer_order_item_additional` WHERE `order_id` = ".$orderId],
	        ['file_name' => $queryType.'-'.$orderId.'-'.'ccc_manufacturer_order_item_part_data.csv', 'sql' => "SELECT * FROM `ccc_manufacturer_order_item_part_data` WHERE `order_id` = ".$orderId]
	    ];
	}

}

?>