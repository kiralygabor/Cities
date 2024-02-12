<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Városok</title>
</head>
<body>
<h1>Városok</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="countyDropdown">Megye:</label>
        <select id="countyDropdown" name="countyDropdown">
            <option value="">Válassz megyét</option>

            <?php
            require_once('CountiesDbTools.php');

            $countiesDbTool = new CountiesDbTools();
            $counties = $countiesDbTool->getAllCounties();

            foreach ($counties as $county) {
                echo '<option value="' . $county['id'] . '">' . $county['name'] . '</option>';
            }
            ?>
        </select>
        <input type="submit" name="submit" value="Submit">
        <form method="post" action="cities.php">
            <input type="text" name="needle" value="">
            <button type="submit" name="btn-search" method="post">Keres</button>
        </form>
    </form>

    <?php
    if (isset($_POST["countyDropdown"])) {
        require_once('CitiesDbTools.php');

        $selectedCountyId = $_POST["countyDropdown"];
        $citiesDbTool = new CitiesDbTools();
        $cities = $citiesDbTool->getCitiesByCountyId($selectedCountyId);
        $population = [503825,360704,334264,642447,399012,417712,467144,527989,294609.370007,299207,189304,1278874,301429,552964,217463,253551,341317,268648];
        $CountySeat = ["Kecskemét", "Pécs", "Békéscsaba", "Miskolc", "Szeged", "Székesfehérvár", "Győr", "Debrecen", "Eger", "Szolnok", "Tatbánya", "Salgótarján", "Budapest", "Kaposvár", "Nyíregyháza", "Szekszárd", "Szombathely", "Veszprém", "Zalaegerszeg"];

        

        if (!empty($cities)) {
            echo '<h2>' . $cities[0]['county'] . ' megye:</h2>';
            $idxPopulation = $cities[0]['id_county'];
            $idxCountySeat = $cities[0]['id_county'];
            echo 'Népesség:' . $population[$idxPopulation-1] . '    Megyeszékhely:' . $CountySeat[$idxCountySeat-1];
            echo '<table>';
            echo '<tr><th>Irányítószám</th><th>Város</th></tr>';
            foreach ($cities as $city) {
                echo '</td><td>' . $city['zip_code'] . '</td><td>' . $city['city'] . '</td></tr>';
            }
            echo '</table>';
        } else {
            echo 'Nincsenek városok ebben a megyében.';
        }
    }
    ?>
</body>
</html>
