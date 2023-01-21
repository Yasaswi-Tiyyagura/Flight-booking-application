<?php

session_start();

if (isset($_SESSION["phonenumber"])) {

    $mysqli = require __DIR__ . "/db.php";

    $sql = "SELECT * FROM users
            WHERE PassengerID = {$_SESSION["phonenumber"]}";

    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();
}

$array = $_POST["num"];

$mysql = require __DIR__ . "/db.php";

foreach($array as $arr ){

    $origin = $arr[0][0];
    $destination = $arr[0][1];
    $departure_date = $arr[0][2];
    $departure_time = $arr[0][3];
    $arrival_date = $arr[0][4];
    $arrival_time = $arr[0][5];
    $price = number_format((float)$arr[0][6], 2, '.', '');;
    $flightname = $arr[0][7];

    $sqlGetFirst = sprintf("SELECT * FROM flight
                    WHERE origin = '%s' and destination = '%s' and departure_date = '%s' and departure_time = '%s' and arrival_date = '%s' and arrival_time = '%s' and price = %d and flightname = '%s'",
                   $origin,
                   $destination,
                   $departure_date,
                   $departure_time,
                   $arrival_date,
                   $arrival_time,
                   $price,
                   $flightname);

    $oldRecord = $mysql->query($sqlGetFirst);

    //print($oldRecord->num_rows);

    if ($oldRecord->num_rows < 1) {

        $sqlQuery = "Insert into flight (origin, destination, departure_date, departure_time, arrival_date, arrival_time, price, flightname) values (?,?,?,?,?,?,?,?)";

        $stmt = $mysql->stmt_init();

        if (!$stmt->prepare($sqlQuery)) {
            die("SQL error: " . $mysql->error);
        }

        $stmt->bind_param("ssssssds",$origin,$destination,$departure_date,$departure_time,$arrival_date,$arrival_time,$price,$flightname);

        $stmt->execute();
    }
}

$sqlGetQuery = "Select * from flight";

$result = $mysql->query($sqlGetQuery);

$rows = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $str = "";
        foreach ($row as $r) {
            $str = $str.$r."+";

        }
        $str = substr($str, 0, -1);
        $arr = explode("+", $str);
        array_push($rows,$arr);
    }
}

echo json_encode($rows);