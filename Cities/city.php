
<?php
   
    require_once('AbstractPage.php');
    require_once('CountiesDbTools.php');
    require_once('CitiesDbTools.php');

    $countiesDbTool = new CountiesDbTools();
    $citiesDbTool = new CitiesDbTools();

    AbstractPage::insertHtmlHead();
    AbstractPage::showExportBtn();
    $counties = $countiesDbTool->getAllCounties();
    AbstractPage::showDropDown($counties);
    AbstractPage::showAddCity();

    if (isset($_POST["countyDropdown"])) 
    {
        $selectedCountyId = isset($_POST["countyDropdown"]) ? $_POST["countyDropdown"] : '';
        $cities = $citiesDbTool->getCitiesByCountyId($selectedCountyId);
        $population = ["",503825,360704,334264,642447,399012,417712,467144,527989,294609,370007,299207,189304,1278874,301429,552964,217463,253551,341317,268648];
        $CountySeat = ["","Kecskemét", "Pécs", "Békéscsaba", "Miskolc", "Szeged", "Székesfehérvár", "Győr", "Debrecen", "Eger", "Szolnok", "Tatbánya", "Salgótarján", "Budapest", "Kaposvár", "Nyíregyháza", "Szekszárd", "Szombathely", "Veszprém", "Zalaegerszeg"];
        $allFlags = $countiesDbTool->getAllFlags();
       
        if (isset($_POST['btn-search'])) {
            $needle = $_POST['needle'];
            $cities = $citiesDbTool->searchCity($needle);
        }
       
 
        if (!empty($cities)) {
            $countyName = $cities[0]['county_name'];
            echo '<h2 class="nev">' . (!empty($countyName) ? $countyName . ' megye:' : '') . '</h2>';
            $idxPopulation = $cities[0]['id_county'];
            $idxCountySeat = $cities[0]['id_county'];
            $idxFlag = $cities[0]['id_county'];
            AbstractPage::showContainer($allFlags, $idxFlag, $population, $idxPopulation, $CountySeat, $idxCountySeat);
            AbstractPage::showMainTable($cities);
        }
    }
 
    if(isset($_POST['delete_city'])) {
        $cityIdToDelete = $_POST['city_id'];
        $citiesDbTool->deleteCityById($cityIdToDelete);
        $cities = $citiesDbTool->getCitiesByCountyId($selectedCountyId);
    }

    if (isset($_POST['modify_city'])) {
       
        $modifyCityId = $_POST['modify_city_id'];
        $cityToModify = $citiesDbTool->getCityById($modifyCityId);
        AbstractPage::showModifyCity($cityToModify, $modifyCityId);
    }

    if (isset($_POST['modify_city_submit'])) {
        $modifyCityId = $_POST['modify_city_id'];
        $modifiedCityName = $_POST['modified_city_name'];
        $modifiedCityZip = $_POST['modified_city_zip'];
        $citiesDbTool->updateCity($modifyCityId, $modifiedCityName, $modifiedCityZip);
        $cities = $citiesDbTool->getCitiesByCountyId($selectedCountyId);
    }
    
 
    if(isset($_POST['add_city'])) {
        $newCityName = $_POST['new_city_name'];
        $newCityZip = $_POST['new_city_zip'];
        $countyId = $_POST['id_county'];
   
        if(!empty($newCityName) && !empty($newCityZip) && !empty($countyId)) {
            $citiesDbTool->addCity($newCityZip, $newCityName, $countyId);
            $cities = $citiesDbTool->getCitiesByCountyId($countyId);
        }
        else {
            echo "Kérlek töltsd ki mindkét mezőt!";
        }
    }

    ?>