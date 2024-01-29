<?php
require_once 'CitiesInterface.php';
require_once 'DB.php';

class DBCities extends DB implements CitiesInterface
{

    /**
     * @param array $data
     * @return void
     */
    public function create(array $data): ?int
    {
        $sql = 'INSERT INTO cities (%s) VALUES (%s)';
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