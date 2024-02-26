<?php

class DB
{
    protected $mysqli;

    function __construct($host = 'localhost', $user = 'root', $password = null)
    {
        $this->mysqli = mysqli_connect($host, $user, $password);
        if (!$this->mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Létrehozzuk az adatbázist, ha még nem létezik
        $this->createDatabase();
    }

    function __destruct()
    {
        $this->mysqli->close();
    }

    protected function createDatabase()
    {
        $sql = "CREATE DATABASE IF NOT EXISTS cities_db";
        }
    }




?>