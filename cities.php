<?php 
//require_once('csv-tools.php');
//require_once('db-tools.php');
require_once('CitiesDbTools.php');
ini_set('memory_limit','1024M');
$FileName  = "zip_codes.csv";
$csvData = getCsvData($FileName);
$result = [];
$citiy = [];
$cities = [];
$header = $csvData[0];
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
    $truncateMakers = $makersDbTool->truncateMaker($maker);
    $errors = [];
    foreach ($makers as $maker){

        $result = $makersDbTool->createMaker($maker);
        if(!$result){
            $errors[] = $maker;
        }
        echo "$maker\n";
    }
    if (!empty($errors)){
        print_r($erorrs);
    }


if (empty($csvData)) {
    echo "Nincs adat.";
    return false;
}



$csvData = getCsvData($FileName);
$makers = getMakers($csvData);
foreach ($makers as $maker){
    $makersDbTool->createMaker($maker);
}
$allMakers = $makersDbTool->getAllMakers();
$cnt = count($allMakers);
//echo $cnt . "sor van;\n";
$rows =  count($makers);
print_r($rows . " sor van.")
*/
?>