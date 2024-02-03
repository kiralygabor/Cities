<?php 
//require_once('csv-tools.php');
//require_once('db-tools.php');
require_once('CitiesDbTools.php');
ini_set('memory_limit','1024M');
$FileName  = "zip_codes.csv";
$csvData = getCsvData($FileName);
$result = [];
$city = [];
$cities = [];
$zipcodes = [];
$header = $csvData[0];
$idxCounty = array_search ('county', $header);
$idxZipCode = array_search ('zip_code', $header);
$idxCity = array_search ('city', $header);
$citiesDbTool = new CitiesDbTools();

function getCsvData($FileName)
{

    if (!file_exists($FileName)) {
        echo "$FileName nem található. ";
        return false;
    }
    $csvFile = fopen($FileName, 'r');
    $lines = [];
    while (! feof($csvFile)) {
        $line = fgetcsv($csvFile);
        $lines[] = $line;
    }
    fclose($csvFile);
    return $lines;
}

function getCities($csvData)
{
    if (empty($csvData)) {
        echo "Nincs adat.";
        return false;
    }
    $city = '';
    $header = $csvData[0];
    $idxCity = array_search ('city', $header);
    foreach ($csvData as $idx => $line) {
        if(!is_array($line)){
            continue;
        }
        if ($idx == 0) {
            continue;
        }
        if ($city != $line[$idxCity]){
            $city = $line[$idxCity];
            $cities[] = $city;
        }
    }
    return $cities;
}
/*
function getZipCodes($csvData)
{
    if (empty($csvData)) {
        echo "Nincs adat.";
        return false;
    }
    $zipCode = '';
    $header = $csvData[0];
    $idxZipCode = array_search ('zip_code', $header);
    foreach ($csvData as $idx => $line) {
        if(!is_array($line)){
            continue;
        }
        if ($idx == 0) {
            continue;
        }
        if ($zipCode != $line[$idxZipCode]){
            $zipCode = $line[$idxZipCode];
            $zipcodes[] = $zipCode;
        }
    }
    return $zipcodes;
}
*/

    $truncateCities = $citiesDbTool->truncateCity();
    $errors = [];
    foreach ($cities as $city)
    {

        $result = $citiesDbTool->createCity($city);
        if(!$result){
            $errors[] = $city;
        }
        echo "$city\n";
    }

    if (!empty($errors)){
        print_r($erorrs);
    }

if (empty($csvData)) {
    echo "Nincs adat.";
    return false;
}


$csvData = getCsvData($FileName);
$cities = getCities($csvData);
foreach ($cities as $city){
    $citiesDbTool->createCity($city);
}
$allCities = $citiesDbTool->getAllCities();
$cnt = count($allCities);
echo $cnt . " sor van;\n";

?>