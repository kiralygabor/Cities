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
    
/*
    function updateCity($data)
    {
    $cityName = $data['name'];
    $result = $this->mysqli->query("UPDATE " . self::DBTABLE ." SET name = $cityName");
    if (!$result) {
        echo "Hiba történt a $cityName beszúrása közben";
        return $result;
    }
    $city = getCitiesByName($mysqli, $cityName);
    return $result;
    }
*/

/*
    function getCity($id)
    {   
    $result = $this->mysqli->query("SELECT * FROM " . self::DBTABLE . " WHERE id = $id");
    $city = $result->fetch_assoc();
    $result ->  free_result();
    return $city;
    }

    function getCityByName($city)
    {
    $result =$this->mysqli->query("SELECT * FROM " . self::DBTABLE . " WHERE city = $city");
    $city = $result->fetch_assoc();
    return $city;
    }

    function getAllCities()
    {
        $result = $this->mysqli->query("SELECT * FROM " . self::DBTABLE);
        $city = $result->fetch_all(MYSQLI_ASSOC);
        $result ->  free_result();
        return $city;
    }

    function delCity($id)
    {
    $result = $this->mysqli->query("DELETE {self::DBTABLE} WHERE id = $id");
    return $result;
    }
*/
    function truncateCity()
    {
        $result = $this->mysqli->query("TRUNCATE TABLE " . self::DBTABLE);
        return $result;
    }

}

?>