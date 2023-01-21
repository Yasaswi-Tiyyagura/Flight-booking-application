<?php

session_start();

$phone = $_SESSION["phonenumber"];

$mysqlk = require __DIR__ . "/db.php";

$sqlSearch = sprintf("SELECT flightID FROM booking
                    WHERE PassengerID = %d",
                   $phone);

$userFlights = $mysqlk->query($sqlSearch);

$rows = [];

if ($userFlights->num_rows > 0) {
    while($row = $userFlights->fetch_assoc()) {
        foreach ($row as $r) {
            array_push($rows,(int)$r);
        }
    }
}

$flight_rows = [];

foreach($rows as $rec){
    $sqlFlights = sprintf("select * from flight where flightID = %d",
        $rec);
    $allFlights = $mysqlk->query($sqlFlights);
    if ($allFlights->num_rows > 0){
        while($row = $allFlights->fetch_assoc()) {
            $str = "";
            foreach ($row as $r) {
                $str = $str.$r."+";
            }
            $str = substr($str, 0, -1);
            $arr = explode("+", $str);
            array_push($flight_rows,$arr);
        }
    }
}

$strResults = json_encode($flight_rows);

//print(json_encode($flight_rows));

?>

<!DOCTYPE html>
<html>
<head>
    <title>Bookings</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
</head>
<body>
    <h1>Here are your flight bookings</h1>
    <br />
    <button type="button" class="btn" onclick="location.href='index.php'">Home</button>
    <table id="demo1"></table>
    <script>
        var parseResult = <?php echo $strResults; ?>;

        if (parseResult.length > 0) {

            var table = "<tr><th>FlightID</th><th>Origin</th><th>Destination</th><th>Departure Date</th><th>Departure Time</th><th>Arrival Date</th><th>Arrival Time</th><th>Price</th><th>Choose Flight</th></tr>";

            for (i = 0; i < parseResult.length; i++) {

                table += "<tr><td>" +
                    parseResult[i][0] +
                    "</td><td>" +
                    parseResult[i][1] +
                    "</td><td>" +
                    parseResult[i][2] +
                    "</td><td>" +
                    parseResult[i][3] +
                    "</td><td>" +
                    parseResult[i][4] +
                    "</td><td>" +
                    parseResult[i][5] +
                    "</td><td>" +
                    parseResult[i][6] +
                    "</td><td>" +
                    parseResult[i][7] +
                    "</td><td>" +
                    parseResult[i][8] +
                    "</td></tr>";
            }

            document.getElementById("demo1").innerHTML = table;
        }
        else {
            alert("No Orders your account. Please place an order!");
        }

    </script>
</body>
</html>

