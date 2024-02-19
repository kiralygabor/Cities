<?php
class CountiesDbTools {
    const DBTABLE = 'counties';

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

    function createCounty($county)
    {
        $result = $this->mysqli->query("INSERT INTO " . self::DBTABLE . " (name) VALUES ('$county')");
        if (!$result) {
            echo "Hiba történt a $county beszúrása közben";

        }
        return $result;
    }

    function createColumnFlag()
        {
            $result = $this->mysqli->query("ALTER TABLE " . self::DBTABLE . " ADD IF NOT EXISTS flag varchar(50)");
            $result = $this->mysqli->query("ALTER TABLE " . self::DBTABLE . " MODIFY flag varchar(50) CHARACTER SET utf8 COLLATE utf8_hungarian_ci;");
            return $result;
        }
   
        function fillColumnFlag()
        {  
            $flags = ["Bacs-Kiskun.png","Baranya.png","Bekes.png","Borsod-Abauj-Zemplen.png","Csongrad-Csanad.png","Fejer.png","Gyor-Moson-Sopron.png","Hajdu-Bihar.png","Heves.png","Jasz-Nagykun-Szolnok.png","Komarom-Esztergom.png","Nograd.png","Pest.png","Somogy.png","Szabolcs-Szatmar.png","Tolna.png","Vas.png","Veszprem.png","Zala.png"];
            $results = [];      
            for ($i = 0; $i < count($flags); $i++) {
                $update = "UPDATE " . self::DBTABLE . " SET flag = 'pics/{$flags[$i]}' WHERE id = '" . ($i+1) . "';";
                try{
                    $result = $this->mysqli->query($update);
                    $results[] = $result;
                } catch ( Exception $e){
                    $e->getMessage();
                }
            echo $update;
            echo '<br>' ;
            }
            $this->mysqli->commit();
            return $results;          
        }
 
        public function getAllFlags()
    {
        $allFlags = [];
 
        $sql = "SELECT flag FROM counties";
        $result = $this->mysqli->query($sql);
 
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $allFlags[] = $row;
            }
        }
 
        return $allFlags;
    }
    
    
    
    function truncateCounty()
    {
        $result = $this->mysqli->query("TRUNCATE TABLE " . self::DBTABLE);
        return $result;
    }

    public function getAllCounties()
    {
        $counties = [];

        $sql = "SELECT * FROM counties";
        $result = $this->mysqli->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $counties[] = $row;
            }
        }

        return $counties;
    }

    public function getCountybyId($countyId)
    {
        $sql = "SELECT * FROM counties WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i",$countyId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
            } else {
                'Not available';
            }

    }
    public function saveCounty($countyName)
    {
        $sql = 'INSERT INTO counties (name) VALUES (?)';
        $stmt = $this->mysqli->prepare($sql);
        $stmt ->bind_param('s',$countyName);

        $stmt->execute();
    }

    public function updateCounty($countyId,$countyName)
    {
        $sql = 'UPDATE counties SET name = ? WHERE id = ?';
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param('si', $countyName, $countyId);

        $stmt->execute();
    }

    public function searchCounty($needle)
    {
        $sql = "SELECT * FROM  counties WHERE name LIKE '%$needle%'";
        $stmt = $this->mysqli->prepare($sql);
        //$stmt->bind_param('s',$needle);

        $result = $this->mysqli->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()){
                $counties[] = $row;
            }
        }

        return $counties;
    }

    public function deleteCounty($countyId)
    {
        $sql = 'DELETE FROM counties WHERE id = ?';
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param('i',$countyId);
    }
}

?>