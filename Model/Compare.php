<?php 
require_once "Model/Core/Main.php";

class Model_Compare extends Model_Core_Main
{
	public function __construct(){
		Parent::__construct();
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

}

?>