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

function updateCitiesWithCounties($citiesDbTool) {
    $queries = [
        "UPDATE cities SET cities.id_county = 1 WHERE cities.zip_code BETWEEN 6000 AND 6528;",
        "UPDATE cities SET cities.id_county = 2 WHERE cities.zip_code BETWEEN 7300 AND 7396;",
        "UPDATE cities SET cities.id_county = 2 WHERE cities.zip_code BETWEEN 7600 AND 7985;",
        "UPDATE cities SET cities.id_county = 3 WHERE cities.zip_code BETWEEN 5500 AND 5948;",
        "UPDATE cities SET cities.id_county = 4 WHERE cities.zip_code BETWEEN 3400 AND 3999;",
        "UPDATE cities SET cities.id_county = 5 WHERE cities.zip_code BETWEEN 6600 AND 6932;",
        "UPDATE cities SET cities.id_county = 6 WHERE cities.zip_code BETWEEN 2400 AND 2490;",
        "UPDATE cities SET cities.id_county = 6 WHERE cities.zip_code BETWEEN 8000 AND 8157;",
        "UPDATE cities SET cities.id_county = 7 WHERE cities.zip_code BETWEEN 9001 AND 9495;",
        "UPDATE cities SET cities.id_county = 8 WHERE cities.zip_code BETWEEN 4000 AND 4977;",
        "UPDATE cities SET cities.id_county = 9 WHERE cities.zip_code BETWEEN 3000 AND 3036;",
        "UPDATE cities SET cities.id_county = 9 WHERE cities.zip_code BETWEEN 3200 AND 3399;",
        "UPDATE cities SET cities.id_county = 10 WHERE cities.zip_code BETWEEN 5000 AND 5476;",
        "UPDATE cities SET cities.id_county = 11 WHERE cities.zip_code BETWEEN 2500 AND 2545;",
        "UPDATE cities SET cities.id_county = 11 WHERE cities.zip_code BETWEEN 2800 AND 2949;",
        "UPDATE cities SET cities.id_county = 12 WHERE cities.zip_code BETWEEN 2640 AND 2699;",
        "UPDATE cities SET cities.id_county = 12 WHERE cities.zip_code BETWEEN 3041 AND 3253;",
        "UPDATE cities SET cities.id_county = 13 WHERE cities.zip_code BETWEEN 2000 AND 2769;",
        "UPDATE cities SET cities.id_county = 14 WHERE cities.zip_code BETWEEN 7400 AND 7589;",
        "UPDATE cities SET cities.id_county = 14 WHERE cities.zip_code BETWEEN 8600 AND 8739;",
        "UPDATE cities SET cities.id_county = 15 WHERE cities.zip_code BETWEEN 4300 AND 4977;",
        "UPDATE cities SET cities.id_county = 16 WHERE cities.zip_code BETWEEN 7020 AND 7228;",
        "UPDATE cities SET cities.id_county = 17 WHERE cities.zip_code BETWEEN 9500 AND 9985;",
        "UPDATE cities SET cities.id_county = 18 WHERE cities.zip_code BETWEEN 8161 AND 8598;",
        "UPDATE cities SET cities.id_county = 19 WHERE cities.zip_code BETWEEN 8353 AND 8395;",
        "UPDATE cities SET cities.id_county = 19 WHERE cities.zip_code BETWEEN 8741 AND 8999;"
    ];

    foreach ($queries as $query) {
        $citiesDbTool->executeQuery($query);
    }
}

truncateCityTable($citiesDbTool,$csvData);
truncateCountyTable($countiesDbTool,$csvData);
updateCitiesWithCounties($citiesDbTool);
?>
