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
            $zipCodes[] = $zipCode;
        }
    }
    return $zipCodes;
}


/*
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

*/


$csvData = getCsvData($fileName);

$countiesDbTool->truncateCounty();
$counties = getCounties($csvData);
foreach ($counties as $county){
    $countiesDbTool->createCounty($county);
}

$citiesDbTool->truncateCity();
$cities = getCities($csvData);
foreach ($cities as $city){
    $citiesDbTool->createCity($city[0],$city[1]);
    echo''. $city[0].''.$city[1];
}

/*
$allCities = $citiesDbTool->getAllCities();
$cnt = count($allCities);
echo $cnt . " sor van;\n";
*/

?>