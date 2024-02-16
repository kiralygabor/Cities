<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Városok</title>
</head>
<body>

<h1>Városok</h1>


<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="setCountyId()">

    <label for="countyDropdown">Megye:</label>
    <select id="countyDropdown" name="countyDropdown">
        <option value="">Válassz megyét</option>
        <?php
        require_once('CountiesDbTools.php');
        $countiesDbTool = new CountiesDbTools();
        require_once('CitiesDbTools.php'); 
        $citiesDbTool = new CitiesDbTools(); 
        $selectedCountyId = null;
        $counties = $countiesDbTool->getAllCounties();
        foreach ($counties as $county) {
            echo '<option value="' . $county['id'] . '">' . $county['name'] . '</option>';
        }
        ?>
    </select>
    <input type="hidden" name="county_id" id="county_id" value=""> 
    <input type="submit" name="submit" value="Submit">
</form>




<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
   
    <?php
    if (isset($_POST["countyDropdown"])) {
        $selectedCountyId = isset($_POST["countyDropdown"]) ? $_POST["countyDropdown"] : '';
    }
    ?>
    <input type="hidden" name="county_id" value="<?php echo isset($selectedCountyId) ? $selectedCountyId : ''; ?>">
    <label for="new_city_name">Új város neve:</label>
    <input type="text" id="new_city_name" name="new_city_name">
    <label for="new_city_zip">Irányítószám:</label>
    <input type="text" id="new_city_zip" name="new_city_zip">
    <input type="hidden" name="id_county" value="<?php echo $selectedCountyId; ?>">
    <input type="submit" name="add_city" value="Hozzáad">
</form>

    <?php
    if (isset($_POST["countyDropdown"])) {
        

        $selectedCountyId = isset($_POST["countyDropdown"]) ? $_POST["countyDropdown"] : '';
        
        $cities = $citiesDbTool->getCitiesByCountyId($selectedCountyId);
        $population = [503825,360704,334264,642447,399012,417712,467144,527989,294609.370007,299207,189304,1278874,301429,552964,217463,253551,341317,268648];
        $CountySeat = ["Kecskemét", "Pécs", "Békéscsaba", "Miskolc", "Szeged", "Székesfehérvár", "Győr", "Debrecen", "Eger", "Szolnok", "Tatbánya", "Salgótarján", "Budapest", "Kaposvár", "Nyíregyháza", "Szekszárd", "Szombathely", "Veszprém", "Zalaegerszeg"];

        

       if (!empty($cities)) {
    echo '<h2>' . $cities[0]['county'] . ' megye:</h2>';
    $idxPopulation = $cities[0]['id_county'];
    $idxCountySeat = $cities[0]['id_county'];
    echo 'Népesség:' . $population[$idxPopulation-1] . '    Megyeszékhely:' . $CountySeat[$idxCountySeat-1];
    echo '<table>';
    echo '<tr><th>Irányítószám</th><th>Város</th><th>Műveletek</th></tr>';
    foreach ($cities as $city) {
        echo '<tr>';
        echo '<td>' . $city['zip_code'] . '</td>';
        echo '<td>' . $city['city'] . '</td>';
        echo '<td><form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '"><input type="hidden" name="city_id" value="' . $city['id'] . '"><input type="submit" name="delete_city" value="Törlés"></form></td>';
        echo '</tr>';
    }
    echo '</table>';
    } 

    }

    if(isset($_POST['delete_city'])) {
        $cityIdToDelete = $_POST['city_id'];
        $citiesDbTool->deleteCityById($cityIdToDelete);
        $cities = $citiesDbTool->getCitiesByCountyId($selectedCountyId);
    }

    if(isset($_POST['add_city'])) {
        $newCityName = $_POST['new_city_name'];
        $newCityZip = $_POST['new_city_zip'];
        $countyId = $selectedCountyId;
    
        if(!empty($newCityName) && !empty($newCityZip) && !empty($countyId)) {
            $citiesDbTool->addCity($newCityName, $newCityZip, $countyId);
            $cities = $citiesDbTool->getCitiesByCountyId($selectedCountyId);
        }
        else {
            echo "Kérlek töltsd ki mindkét mezőt!";
        }
    }
    
    ?>
    
</form>
</body>
</html>
