<?php
require_once('CitiesDbTools.php');
require_once('CountiesDbTools.php');
require_once('DBCounties.php');
require_once('DBCities.php');
ini_set('memory_limit','1024M');
$fileName  = "zip_codes.csv";
$csvData = getCsvData($fileName);
$result = [];
$county = [];
$counties = [];
$city = [];
$cities = [];
$header = $csvData[0];
$idxCounty = array_search ('county', $header);
$idxZipCode = array_search ('zip_code', $header);
$idxCity = array_search ('city', $header);
$citiesDbTool = new CitiesDbTools();
$countiesDbTool = new CountiesDbTools();
$dbCounties = new DBCounties();
$dbCities = new DBCities();
$createCountiesTable = $dbCounties->createTable();
$createCitiesTable = $dbCities->createTable();
$createColumnFlag = $countiesDbTool->createColumnFlag();
$fillColumnFlag = $countiesDbTool->fillColumnFlag();

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
    $header = $csvData[0];
    $idxCounty = array_search ('county', $header);
    foreach ($csvData as $idx => $line) {
        if(!is_array($line)){
            continue;
        }
        if ($idx == 0) {
            continue;
        }
        if ($county != $line[$idxCounty]){
            $county = $line[$idxCounty];
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
    $idxCity = array_search ('city', $header);
    $idxZipCode = array_search ('zip_code', $header);
    foreach ($csvData as $idx => $line) {
        if(!is_array($line)){
            continue;
        }
        if ($idx == 0) {
            continue;
        }
            $city = $line[$idxCity];
            $zipCode = $line[$idxZipCode];
            $cities[] = [$zipCode,$city];
    }
    return $cities;
}

function truncateCityTable($citiesDbTool,$csvData){
    $citiesDbTool->truncateCity();
    $cities = getCities($csvData);
    foreach ($cities as $city){
        $citiesDbTool->createCity($city[0],$city[1]);
    }
}

function truncateCountyTable($countiesDbTool,$csvData){
    $countiesDbTool->truncateCounty();
    $counties = getCounties($csvData);
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

truncateCityTable($citiesDbTool,$csvData);
truncateCountyTable($countiesDbTool,$csvData);
updateCitiesIdCounty($csvData, $citiesDbTool);

?>
