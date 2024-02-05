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

    function truncateCounty()
    {
        $result = $this->mysqli->query("TRUNCATE TABLE " . self::DBTABLE);
        return $result;
    }
}

?>