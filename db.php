<?php

$username = 'root';
$password = '';
$dbname = 'assignment4';
$host = 'localhost:3306';


$mysqli = new mysqli(hostname: $host,
                        username: $username,
                        password: $password,
                        database: $dbname);

if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

return $mysqli;