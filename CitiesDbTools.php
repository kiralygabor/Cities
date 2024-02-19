<?php
class CitiesDbTools {
    const DBTABLE = 'cities';

    private $mysqli;

    function __construct($host = 'localhost', $user = 'root', $password = null, $db = 'cities_db')
    {
        $this->mysqli = new mysqli($host, $user, $password, $db);
        if ($this->mysqli->connect_errno){
            throw new Exception($this->mysqli->connect_errno);
        }
    }

    function __destruct()
    {
        $this->mysqli->close();
    }
    
    function createCity($zipCode,$city)
    {
        $sql = "INSERT INTO " . self::DBTABLE . " (zip_code,city) VALUES ('$zipCode','$city')";
        $result = $this->mysqli->query( $sql );
        if (!$result) {
            echo "Hiba történt a $city beszúrása közben";

        }
        return $result;
    }

    public function getCitiesByCountyId($countyId)
    {
        $cities = [];

        $sql = "SELECT cities.*, counties.name as county FROM cities JOIN counties on cities.id_county = counties.id WHERE id_county = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $countyId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $cities[] = $row;
            }
        }

        return $cities;
    }

    
    public function getCitiesByCityId($cityId)
    {
        $sql = "SELECT * FROM cities WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $cityId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
            } else {
                'Postal code is not available';
            }

    }

    function deleteCityById($cityId)
    {
        $sql = "DELETE FROM " . self::DBTABLE . " WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $cityId);
        $result = $stmt->execute();
        if (!$result) {
            echo "Hiba történt a város törlése közben";
        }
        return $result;
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
        $sql = "INSERT INTO " . self::DBTABLE . " (city, zip_code, id_county) VALUES (?, ?, ?)";
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

    $sql = "SELECT * FROM cities WHERE city LIKE '%$needle%' OR zip_code LIKE '%$needle%'";
    $result = $this->mysqli->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cities[] = $row;
        }
    }

    return $cities;
}
}
?>
