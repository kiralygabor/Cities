<?php
class CitiesDbTools {
    const DBTABLE = 'cities';
 
    private $mysqli;
 
    function __construct($host = 'localhost', $user = 'root', $password = null, $db = 'cities_db')
    {
        $this->mysqli = new mysqli($host, $user, $password, $db);
        if ($this->mysqli->connect_errno){
            throw new Exception("Hiba a kapcsolódás során: " . $this->mysqli->connect_error);
        }
    }
 
    function __destruct()
    {
        $this->mysqli->close();
    }
   
    function createCity($zipCode,$city)
    {
        $sql = "INSERT INTO " . self::DBTABLE . " (zip_code,city) VALUES (?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ss", $zipCode, $city);
        $result = $stmt->execute();
        if (!$result) {
            echo "Hiba történt a város beszúrása közben";
            return false;
        }
        return true;
    }
 
    public function getCitiesByCountyId($countyId)
    {
        $cities = [];
 
        $query = "SELECT cities.*, counties.name AS county_name FROM cities INNER JOIN counties ON cities.id_county = counties.id WHERE cities.id_county = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("i", $countyId);
        $stmt->execute();
        $result = $stmt->get_result();
        $cities = [];
        while ($row = $result->fetch_assoc()) {
            $cities[] = $row;
        }
        $stmt->close();
        return $cities;
   
    }
 
    function deleteCityById($cityId)
    {
        $sql = "DELETE FROM " . self::DBTABLE . " WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $cityId);
        $result = $stmt->execute();
        if (!$result) {
            echo "Hiba történt a város törlése közben";
            return false;
        }
        return true;
    }
 
    function truncateCity()
    {
        $result = $this->mysqli->query("TRUNCATE TABLE " . self::DBTABLE);
        return $result;
    }
 
    public function updateCitiesIdCounty($start, $end, $idCounty) {
        $sql = "UPDATE cities SET id_county = $idCounty WHERE zip_code BETWEEN $start AND $end";
        $result = $this->mysqli->query($sql);
 
        if (!$result) {
            echo "Error updating cities: " . $this->mysqli->error;
            return false;
        }
 
        return true;
    }
 
    public function addCity($zipCode, $city, $countyId) {
        $sql = "INSERT INTO " . self::DBTABLE . " (zip_code, city, id_county) VALUES (?, ?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ssi", $zipCode, $city, $countyId);
        $result = $stmt->execute();
 
        if (!$result) {
            echo "Error adding city: " . $this->mysqli->error;
            return false;
        }
 
        return true;
    }
 
    public function searchCity($needle)
{
    $cities = [];
 
    $sql = "SELECT cities.*, counties.name AS county_name FROM cities LEFT JOIN counties ON cities.id_county = counties.id WHERE cities.city LIKE '%$needle%' OR cities.zip_code LIKE '%$needle%'";
    $result = $this->mysqli->query($sql);
 
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cities[] = $row;
        }
    }
 
    return $cities;
}


    function updateCity($cityId, $cityName, $zipCode) {
        $sql = "UPDATE " . self::DBTABLE . " SET city = ?, zip_code = ? WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ssi", $cityName, $zipCode, $cityId);
        $result = $stmt->execute();

        if (!$result) {
            echo "Hiba történt a város módosítása közben";
            return false;
        }

        return true;
    }

    public function getCityById($cityId) {
        $query = "SELECT * FROM " . self::DBTABLE . " WHERE id = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("i", $cityId);
        $stmt->execute();
        $result = $stmt->get_result();
        $city = $result->fetch_assoc();
        $stmt->close();
        return $city;
    }

    public function getAll(): array
    {
        $query = "SELECT * FROM cities ORDER BY name";

        return $this->mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
    }
}

?>