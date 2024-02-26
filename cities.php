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

if (isset($_POST['import-btn']) && isset($_FILES['input-file']['tmp_name'])) {
    $tmpFilePath = $_FILES['input-file']['tmp_name'];
    $csvtools->importCsv($tmpFilePath, $citiesDbTool, $countiesDbTool);
    
    // Csak akkor hajtódik végre az alábbi műveletek, ha történt importálás
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
}

if(isset($_POST['delete-tables-btn'])) {
    // Táblák törlése
    $citiesDbTool->truncateCity();
    $countiesDbTool->truncateCounty();
    // Visszaállítás a kezdeti állapotba
    $dbCounties->createTable();
    $dbCities->createTable();
    $countiesDbTool->createColumnFlag();
    $countiesDbTool->fillColumnFlag();
}
if(isset($_POST['create-database'])) {
    $dbCounties->createTable();
    $dbCities->createTable();
    $countiesDbTool->createColumnFlag();
    $countiesDbTool->fillColumnFlag();
}

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="fontawesome/css/all.css" type="text/css">
    <link rel="stylesheet" href="css/styles.css">
    <title>Document</title>
</head>
<body>
<button><a href="city.php"><i class="fa fa-home" title="Kezdőlap"></i></a></button>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="input-file">
    <button type="submit" name="import-btn">Import</button>
    <button type="submit" name="delete-tables-btn">Táblák Törlése</button>
    <button type="submit" name="create-database">Adatbázis Létrehozása</button>
</form>
</body>
</html>