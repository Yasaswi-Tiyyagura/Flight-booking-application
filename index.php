<?php

session_start();

if (isset($_SESSION["phonenumber"])) {

    $mysqli = require __DIR__ . "/db.php";

    $sql = "SELECT * FROM users
            WHERE PassengerID = {$_SESSION["phonenumber"]}";

    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
</head>
<body>

    <h1>Home</h1>

    <?php if (isset($user)): ?>

    <p>
        Hello <?= htmlspecialchars($user["FirstName"]) ?>
    </p>


    <button type="button" class="btn" onclick="loadDoc()">Get All Flights</button>
    <br />
    <br />
    <button type="button" class="btn1" onclick="location.href='bookings.php'">Book a Flight</button>
    <button type="button" class="btn1" onclick="location.href='flightstatus.php'">Flight Status</button>
    <br /><br />
    <table id="demo"></table>
    <script>

        var globalLength;
        var k;
        var test;

        function loadDoc() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    myFunction(this);
                }
            };
            xhttp.open("GET", "cd_catalog.xml", true);
            xhttp.send();
        }

        function myFunction(xml) {

            var i;
            var xmlDoc = xml.responseXML;
            var table = "<tr><th>FlightID</th><th>Origin</th><th>Destination</th><th>Departure Date</th><th>Departure Time</th><th>Arrival Date</th><th>Arrival Time</th><th>Price</th><th>Choose Flight</th></tr>";
            var x = xmlDoc.getElementsByTagName("CD");
            globalLength = x.length;
            globalArray = new Array();

            for (i = 0; i < x.length; i++) {

                internalArray = new Array(
                    [x[i].getElementsByTagName("ORIGIN")[0].childNodes[0].nodeValue,
                        x[i].getElementsByTagName("DESTINATION")[0].childNodes[0].nodeValue,
                        x[i].getElementsByTagName("COUNTRY")[0].childNodes[0].nodeValue,
                        x[i].getElementsByTagName("COMPANY")[0].childNodes[0].nodeValue,
                        x[i].getElementsByTagName("PRICE")[0].childNodes[0].nodeValue,
                        x[i].getElementsByTagName("YEAR")[0].childNodes[0].nodeValue,
                        x[i].getElementsByTagName("price")[0].childNodes[0].nodeValue,
                        x[i].getElementsByTagName("flight")[0].childNodes[0].nodeValue
                    ]);

                globalArray.push(internalArray);
            }

            jQuery.ajax({
                async: false,
                type: "POST",
                url: 'addRecords.php',
                data: { num: globalArray },
                success: function (response) {
                    test = response;
                }
            });

            var parseResult = JSON.parse(test);

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

            document.getElementById("demo").innerHTML = table;

        }

    </script>

    <p>
        <a href="logout.php">Log out</a>
    </p>

    <?php else: ?>

    <p>
        <a href="login.php">Log in</a> or <a href="registration.html">Register</a>
    </p>

    <?php endif; ?>

</body>
</html>