<?php
require_once "CitiesDbTools.php";
header('Content-Type: application/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="cities.csv"');
$citiesDbTools = new CitiesDbTools();
$cities = $citiesDbTools->getAll();

$csvFile = fopen('php://output','w');
fputcsv($csvFile, ['id', 'zip_code' , 'city', 'county_id']);
foreach ($cities as $city) {
    fputcsv($csvFile,$city);
}
fclose($csvFile);

?>