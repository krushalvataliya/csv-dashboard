<?php 
require_once 'Controller/Core/Action.php';
require_once 'Model/Compare.php';


class Controller_Compare extends Controller_Core_Action
{
    protected $comparisonResults = [];
    protected $modelCompare = null;

    public function IndexAction()
    {	
        $this->getTemplete('compare/index.phtml');
    }

    public function ViewResultAction()
    {
        $this->getTemplete('compare/result.phtml');
    }
    
    public function fetchCsvSuggestionsAction()
    {
        $csvDir = Kv::getCsvDir();
        $searchTerm = $this->getRequest()->getParam('q', '');
        $matches = [];
        $files = scandir($csvDir);
        foreach ($files as $file) {
            $matches[] = $file;
        }
        $this->getResponse()->jsonResponse($matches);
    }

    public function compareCsvAction()
    {
        try {
            $request = $this->getRequest();
            if (!$request->isPost()) {
                throw new Exception("Invalid Request.", 1);
            }
            $csvFile1 = trim($request->getPost('csvFile1'));
            $csvFile2 = trim($request->getPost('csvFile2'));

            if (empty($csvFile1) || empty($csvFile2)) {
                throw new Exception('Please provide both CSV filenames.', 1);
            }
            $csvDir = Kv::getCsvDir();
            $csvPath1 = $csvDir . $csvFile1;
            $csvPath2 = $csvDir . $csvFile2;

            if (!file_exists($csvPath1)) {
                throw new Exception("CSV File 1 '{$csvFile1}' not found.", 1);
            }

            if (!file_exists($csvPath2)) {
                throw new Exception("CSV File 2 '{$csvFile2}' not found.", 1);
            }

            $csvData1 = $this->getComapreModel()->csvToArray($csvPath1);
            $csvData2 = $this->getComapreModel()->csvToArray($csvPath2);

            if (!$csvData1 || !$csvData2) {
                throw new Exception('Error reading CSV files.', 1);
            }

            if (empty($csvData1[0]) || empty($csvData2[0])) {
                throw new Exception('CSV files are empty or headers are missing.', 1);
            }

            $headerDiff = array_diff(array_keys($csvData1[0]), array_keys($csvData2[0]));

            $comparisonResults = [];

            if (!empty($headerDiff)) {
                $comparisonResults['headerDiff'] = [
                    'csvFile1Headers' => array_keys($csvData1[0]),
                    'csvFile2Headers' => array_keys($csvData2[0]),
                ];
            } else {
                $comparisonResults['headerDiff'] = [];
            }

            $contentDiff = $this->getComapreModel()->compareCsvContents($csvData1, $csvData2);
            $comparisonResults['contentDiff'] = $contentDiff;

            $this->setComparisonResults($comparisonResults);

        } catch (Exception $e) {
            $this->getMessage()->addMessage('Error: '.$e->getMessage(),  Model_Core_Message::FAILURE);
	        return $this->redirect('index');
        }
        return $this->ViewResultAction();
    }    

    public function getComparisonResults()
    {
    	return $this->comparisonResults;
    }

    public function setComparisonResults($comparisonResults)
    {
    	$this->comparisonResults = $comparisonResults;
    	return $this;
    }

    public function getComapreModel()
    {
        if (!$this->modelCompare) {
        $this->modelCompare = new Model_Compare();
        }
        return $this->modelCompare;
    }


}
