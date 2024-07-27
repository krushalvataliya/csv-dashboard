<?php 
require_once 'Controller/Core/Action.php';
require_once 'Model/Export.php';

class Controller_Compare extends Controller_Core_Action
{
	public function IndexAction()
	{	
		$this->getTemplete('compare/index.phtml');
	}
	public static function getCsvDir()
	{
		return './var/uploads/';
	}


	public function fetchCsvSuggestionsAction()
	{
		$csvDir = self::getCsvDir();
		$searchTerm = $_GET['q'] ?? '';
		$matches = [];
		$files = scandir($csvDir);
		foreach ($files as $file) {
		    if (strpos($file, $searchTerm) !== false && pathinfo($file, PATHINFO_EXTENSION) === 'csv') {
		        $matches[] = $file;
		    }
		}
		header('Content-Type: application/json');
		if (!empty($matches)) {
		    echo json_encode($matches);
		} else {
		    echo json_encode([]);
		}
	}

	public function compareCsvAction()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		    $csvFile1 = trim($_POST['csvFile1']);
		    $csvFile2 = trim($_POST['csvFile2']);

		    if (empty($csvFile1) || empty($csvFile2)) {
		        die('Please provide both CSV filenames.');
		    }
			$csvDir = self::getCsvDir();
		    $csvPath1 = $csvDir . $csvFile1;
		    $csvPath2 = $csvDir . $csvFile2;

		    if (!file_exists($csvPath1)) {
		        die("CSV File 1 '{$csvFile1}' not found.");
		    }

		    if (!file_exists($csvPath2)) {
		        die("CSV File 2 '{$csvFile2}' not found.");
		    }

		    $csvData1 = $this->csvToArray($csvPath1);
		    $csvData2 = $this->csvToArray($csvPath2);

		    if (!$csvData1 || !$csvData2) {
		        die('Error reading CSV files.');
		    }

		    if (empty($csvData1[0]) || empty($csvData2[0])) {
		        die('CSV files are empty or headers are missing.');
		    }

		    $headerDiff = array_diff(array_keys($csvData1[0]), array_keys($csvData2[0]));

		    if (!empty($headerDiff)) {
		        echo "Headers are different:<br>";
		        echo "CSV File 1 Headers: " . implode(', ', array_keys($csvData1[0])) . "<br>";
		        echo "CSV File 2 Headers: " . implode(', ', array_keys($csvData2[0])) . "<br><br>";
		    } else {
		        echo "Headers are identical.<br><br>";
		    }

		    $contentDiff = $this->compareCsvContents($csvData1, $csvData2);

		    if (!empty($contentDiff)) {
		        echo "Content differences found:<br>";
		        foreach ($contentDiff as $diff) {
		            echo "Difference: <br><pre>";
		            print_r($diff);
		            echo "<br></pre>";
		        }
		    } else {
		        echo "No content differences found.<br>";
		    }
		}
	}

	public function compareCsvContents($csvData1, $csvData2)
	{
		$diff = [];
	    foreach ($csvData1 as $index => $row1) {
	        if (isset($csvData2[$index])) {
	            $row2 = $csvData2[$index];
	            $rowDiff = [];

	            foreach ($row1 as $field => $value1) {
	                $value2 = $row2[$field] ?? null;
	                if ($value1 !== $value2) {
	                    $rowDiff[$field] = [$value1, $value2];
	                }
	            }
	            if (!empty($rowDiff)) {
	                $diff[] = [
	                    'row' => $index + 1,
	                    'differences' => $rowDiff
	                ];
	            }
	        } else {
	            $diff[] = [
	                'row' => $index + 1,
	                'differences' => array_fill_keys(array_keys($row1), [$row1, null])
	            ];
	        }
	    }

	    for ($index = count($csvData1); $index < count($csvData2); $index++) {
	        $row2 = $csvData2[$index];
	        $diff[] = [
	            'row' => $index + 1,
	            'differences' => array_fill_keys(array_keys($row2), [null, $row2])
	        ];
	    }

	    return $diff;
	}

	public function csvToArray($filename)
    {
        $csvData = [];
        $row = 0;
        if (($handle = fopen($filename, "r")) !== FALSE) {
	        $csvHeader = [];
	        while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
	            if ($row == 0) {
	                $csvHeader = $data;
	                $row++;
	                continue;
	            }
	            $sRow = array_combine($csvHeader, $data);
	            $csvData[] = $sRow;
	            $row++;
	        }
	        fclose($handle);
	    }
		return $csvData;
    }
}

?>