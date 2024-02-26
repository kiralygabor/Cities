<?php
require_once 'CountiesInterface.php';
require_once 'DB.php';

class DBCounties extends DB implements CountiesInterface
{

    public function createTable(){
        $query = 'USE cities_db';
        $this->mysqli->query($query);
        $query = 'CREATE TABLE IF NOT EXISTS counties (id int NOT NULL AUTO_INCREMENT PRIMARY KEY, name varchar(50) NOT NULL);';
        return $this->mysqli->query($query);
        
    }

    public function create(array $data): ?int
    {
        $sql = 'INSERT INTO counties (%s) VALUES (%s)';
        $fields = '';
        $values = '';
        foreach ($data as $field => $value) {
            if ($fields > '') {
                $fields .= ',' . $field;
            } else
                $fields .= $field;

            if ($values > '') {
                $values .= ',' . "'$value'";
            } else
                $values .= "'$value'";
        }
        $sql = sprintf($sql, $fields, $values);
        $this->mysqli->query($sql);

        $lastInserted = $this->mysqli->query("SELECT LAST_INSERT_ID() id;")->fetch_assoc();

        return $lastInserted['id'];
    }

   
}

?>