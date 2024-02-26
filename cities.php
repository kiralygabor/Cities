<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="fontawesome/css/all.css" type="text/css">
    <title>Document</title>
</head>
<body>
<button><a href="city.php"><i class="fa fa-home" title="Kezdőlap"></i></a></button>
</body>
</html>

<?php
require_once('CsvTools.php');
require_once('CitiesDbTools.php');
require_once('CountiesDbTools.php');
require_once('DBCounties.php');
require_once('DBCities.php');

$csvtools = new CsvTools();
$citiesDbTool = new CitiesDbTools();
$countiesDbTool = new CountiesDbTools();
$dbCounties = new DBCounties();
$dbCities = new DBCities();

$csvData = $csvtools->getCsvData($csvtools::FILENAME);
$getCounties = $csvtools->getCounties($csvData);
$getCities = $csvtools->getCities($csvData);

$truncateCityTable = $csvtools->truncateCityTable($citiesDbTool,$csvData);
$truncateCountyTable = $csvtools->truncateCountyTable($countiesDbTool,$csvData);
$updateCitiesIdCounty = $csvtools->updateCitiesIdCounty($csvData, $citiesDbTool);

$createCountiesTable = $dbCounties->createTable();
$createCitiesTable = $dbCities->createTable();
$createColumnFlag = $countiesDbTool->createColumnFlag();
$fillColumnFlag = $countiesDbTool->fillColumnFlag();

?>
