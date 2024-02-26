<?php

ini_set('memory_limit','1024M');

class CsvTools {
    
    const FILENAME  = "zip_codes.csv";
    public $csvData;
    public $result = [];
    public $county = [];
    public $counties = [];
    public $city = [];
    public $cities = [];
    public $header;  
    public $idxCounty; 
    public $idxZipCode;  
    public $idxCity; 

    function __construct(){
        $this->csvData = $this->getCsvData(self::FILENAME);
        $this->header = $this->csvData[0];
        $this->idxCounty = array_search ('county', $this->header);
        $this->idxZipCode = array_search ('zip_code', $this->header);
        $this->idxCity = array_search ('city', $this->header);
    }

    function getCsvData($fileName)
    {
        if (!file_exists($fileName)) {
            echo "$fileName nem található. ";
            return false;
        }
        $csvFile = fopen($fileName, 'r');
        $lines = [];
        while (! feof($csvFile)) {
            $line = fgetcsv($csvFile);
            $lines[] = $line;
        }
        fclose($csvFile);
        return $lines;
    }

    function getCounties($csvData)
    {
        if (empty($csvData)) {
            echo "Nincs adat.";
            return false;
        }
        $county = '';
        foreach ($this->csvData as $idx => $line) {
            if(!is_array($line)){
                continue;
            }
            if ($idx == 0) {
                continue;
            }
            if ($county != $line[$this->idxCounty]){
                $county = $line[$this->idxCounty];
                $counties[] = $county;
            }
        }
        return $counties;
    }
    function getCities($csvData)
    {
        if (empty($csvData)) {
            echo "Nincs adat.";
            return false;
        }
        $city = '';
        $header = $csvData[0];
        foreach ($csvData as $idx => $line) {
            if(!is_array($line)){
                continue;
            }
            if ($idx == 0) {
                continue;
            }
                $city = $line[$this->idxCity];
                $zipCode = $line[$this->idxZipCode];
                $cities[] = [$zipCode,$city];
        }
        return $cities;
    }
    
    function truncateCityTable($citiesDbTool,$csvData){
        $citiesDbTool->truncateCity();
        $cities = $this->getCities($csvData);
        foreach ($cities as $city){
            $citiesDbTool->createCity($city[0],$city[1]);
        }
    }
    
    function truncateCountyTable($countiesDbTool,$csvData){
        $countiesDbTool->truncateCounty();
        $counties = $this->getCounties($csvData);
        foreach ($counties as $county){
            $countiesDbTool->createCounty($county);
        }
    }
    
    function updateCitiesIdCounty($csvData, $citiesDbTool){
        $updates = [
            [6000, 6528, 1],
            [7300, 7396, 2],
            [7600, 7985, 2],
            [5500, 5948, 3],
            [3400, 3999, 4],
            [6600, 6932, 5],
            [2400, 2490, 6],
            [8000, 8157, 6],
            [9001, 9495, 7],
            [4000, 4977, 8],
            [3000, 3036, 9],
            [3200, 3399, 9],
            [5000, 5476, 10],
            [2500, 2545, 11],
            [2800, 2949, 11],
            [2640, 2699, 12],
            [3041, 3253, 12],
            [2000, 2769, 13],
            [7400, 7589, 14],
            [8600, 8739, 14],
            [4300, 4977, 15],
            [7020, 7228, 16],
            [9500, 9985, 17],
            [8161, 8598, 18],
            [8353, 8395, 19],
            [8741, 8999, 19]
        ];
        foreach ($updates as $update) {
            list($start, $end, $idCounty) = $update;
            $citiesDbTool->updateCitiesIdCounty($start, $end, $idCounty);
        }
    }
}



?>