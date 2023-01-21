<?php

session_start();

if (isset($_SESSION["phonenumber"])) {

    $mysqli = require __DIR__ . "/db.php";

    $sql = "SELECT * FROM users
            WHERE PassengerID = {$_SESSION["phonenumber"]}";

    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $is_flights = false;

    $mysqlk = require __DIR__ . "/db.php";

    $sqlSearch = sprintf("SELECT * FROM flight
                    WHERE origin = '%s' and destination = '%s' and departure_date = '%s' and arrival_date = '%s'",
                   $_POST["arrival_place"],
                   $_POST["departure_place"],
                   $_POST["departure_date"],
                   $_POST["arrival_date"]);

    $sqlSearchPlaceOnly = sprintf("SELECT * FROM flight
                    WHERE origin = '%s' and destination = '%s'",
                   $_POST["arrival_place"],
                   $_POST["departure_place"]);

    $userFlights = $mysqlk->query($sqlSearch);

    $userFlightsPlaceOnly = $mysqlk->query($sqlSearchPlaceOnly);

    $rows = [];

    if ($userFlights->num_rows > 0) {
        while($row = $userFlights->fetch_assoc()) {
            $str = "";
            foreach ($row as $r) {
                $str = $str.$r."+";

            }
            $str = substr($str, 0, -1);
            $arr = explode("+", $str);
            array_push($rows,$arr);
        }
        if(isset($_POST["btn_round_trip"])){

            $sqlRoundSearch = sprintf("SELECT * FROM flight
                    WHERE origin = '%s' and destination = '%s' and departure_date = '%s' and arrival_date = '%s'",
                   $_POST["departure_place"],
                   $_POST["arrival_place"],
                   $_POST["departure_date"],
                   $_POST["arrival_date"]);

            $userRoundFlights = $mysqlk->query($sqlRoundSearch);

            if ($userRoundFlights->num_rows > 0){
                while($row = $userRoundFlights->fetch_assoc()) {
                    $str = "";
                    foreach ($row as $r) {
                        $str = $str.$r."+";

                    }
                    $str = substr($str, 0, -1);
                    $arr = explode("+", $str);
                    array_push($rows,$arr);
                }
            }
        }
    }
    else if ($userFlightsPlaceOnly->num_rows > 0){
        while($row = $userFlightsPlaceOnly->fetch_assoc()) {
            $str = "";
            foreach ($row as $r) {
                $str = $str.$r."+";

            }
            $str = substr($str, 0, -1);
            $arr = explode("+", $str);
            array_push($rows,$arr);
        }
        if(isset($_POST["btn_round_trip"])){

            $sqlSearchRoundPlaceOnly = sprintf("SELECT * FROM flight
                    WHERE origin = '%s' and destination = '%s'",
                   $_POST["departure_place"],
                   $_POST["arrival_place"]);

            $userRoundFlightsPlace = $mysqlk->query($sqlSearchRoundPlaceOnly);

            if ($userRoundFlightsPlace->num_rows > 0){
                while($row = $userRoundFlightsPlace->fetch_assoc()) {
                    $str = "";
                    foreach ($row as $r) {
                        $str = $str.$r."+";

                    }
                    $str = substr($str, 0, -1);
                    $arr = explode("+", $str);
                    array_push($rows,$arr);
                }
            }
        }
    }

    $strResults = json_encode($rows);

    $is_flights = false;
}

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
    <h1>Here are your flights</h1>
    <br />
    <?php if ($is_flights): ?>
    <em>No Flights Available. Modify your search</em>
    <?php endif; ?>
    <br />
    <button type="button" class="btn" onclick="location.href='bookings.php'">Back to Search</button>
    <button type="button" class="btn2" id="radioButton" >Add to Cart</button>
    <button type="button" class="btn3" id="viewCart" >View Cart</button>
    <button type="button" class="btn4" id="placeOrder">Place Order</button>
    <br />
    <br />
    <button type="button" class="btn" onclick="location.href='index.php'">Home</button>
    <table id="demo1"></table>
    <table id="demo2"></table>
    <p id="para"></p>
    <script>
        var parseResult = <?php echo $strResults; ?>;
        var table = "<tr><th>FlightID</th><th>Origin</th><th>Destination</th><th>Departure Date</th><th>Departure Time</th><th>Arrival Date</th><th>Arrival Time</th><th>Price</th><th>Choose Flight</th></tr>";
        var i;
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
                    '<input type="radio" value= "'+i+'" name="radio' + i + '">' + parseResult[i][8] +
                    "</td></tr>";
        }

        document.getElementById("demo1").innerHTML = table;

        var globalLength = parseResult.length;
        var cartArray = [];

        function radioButton(){
            radioButtonsArray = [];
            resultArray = [];
            for (i = 0; i < globalLength; i++){
                radioButtonsArray.push(document.querySelectorAll('input[name="radio' + i + '"]'));
            }
            if (radioButtonsArray.length > 0) {
                for (j = 0; j < radioButtonsArray.length; j++) {
                    for (const radioButton of radioButtonsArray[j]) {
                        if (radioButton.checked) {
                            resultArray.push(parseInt(radioButton.value));
                        }
                    }
                }
            }
            else {
                alert("No Items selected to add to cart");
            }
            cartArray = resultArray;
        }

        document.getElementById("radioButton").addEventListener("click", radioButton);

        var viewCartArr = [];

        var buyFlightIdArr = [];

        var total_price = 0;

        function viewCart() {
            for (k = 0; k < parseResult.length; k++) {
                if (cartArray.includes(k)) {
                    viewCartArr.push(parseResult[k]);
                }
            }
            showcart(viewCartArr);
        }

        function showcart(viewCartArr) {

            var table1 = "<tr><th>FlightID</th><th>Origin</th><th>Destination</th><th>Departure Date</th><th>Departure Time</th><th>Arrival Date</th><th>Arrival Time</th><th>Price</th><th>Choose Flight</th></tr>";

            for (i = 0; i < viewCartArr.length; i++) {

                buyFlightIdArr.push(parseInt(viewCartArr[i][0]));

                total_price = Number(total_price) + Number(viewCartArr[i][7]);

                table1 += "<tr><td>" +
                    viewCartArr[i][0] +
                    "</td><td>" +
                    viewCartArr[i][1] +
                    "</td><td>" +
                    viewCartArr[i][2] +
                    "</td><td>" +
                    viewCartArr[i][3] +
                    "</td><td>" +
                    viewCartArr[i][4] +
                    "</td><td>" +
                    viewCartArr[i][5] +
                    "</td><td>" +
                    viewCartArr[i][6] +
                    "</td><td>" +
                    viewCartArr[i][7] +
                    "</td><td>" +
                    viewCartArr[i][8] +
                    "</td></tr>";
            }

            var par = "<p> Total Price: " + total_price + "</p>";

            console.log(buyFlightIdArr);

            document.getElementById("demo1").remove();
            document.getElementById("demo2").innerHTML = table1;
            document.getElementById("para").innerHTML = par;

        }

        document.getElementById("viewCart").addEventListener("click", viewCart);

        function placeOrder() {
            jQuery.ajax({
                async: false,
                type: "POST",
                url: 'placeOrder.php',
                data: { cartArray: JSON.stringify(buyFlightIdArr) },
                success: function (response) {
                    test = response;
                    if (test == "missed") {
                        alert("One or more flights failed to place order");
                    }
                    else if (test == "booked") {
                        alert("Placed Order Successfully");
                    }
                    else if (test == "not booked") {
                        alert("Order not placed");
                    }
                }
            });
        }

        document.getElementById("placeOrder").addEventListener("click", placeOrder);

    </script>
</body>
</html>
