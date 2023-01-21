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
    <title>Bookings</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
</head>
<body>
    <h1>Search Flights</h1>

    <?php if (isset($user)): ?>

    <p>
        Hello <?= htmlspecialchars($user["FirstName"]) ?>, you can search for your flights here!
    </p>

    <form action="bookings-search.php" class=" = "btn" method="post" id="bookings" novalidate>
        <div>
            <label for="btn_round_trip">Search for Round Trip</label>
            <input type="radio" name="btn_round_trip" id="btn_round_trip" value="checked" />
        </div>
        <br />
        <div>
            <label for="arrival_place">Arrival</label>
            <input type="text" name="arrival_place" id="arrival_place" />
        </div>
        <div>
            <label for="departure_place">Departure</label>
            <input type="text" name="departure_place" id="departure_place" />
        </div>
        <div>
            <label for="arrival_date">Arrival Date</label>
            <input type="text" name="arrival_date" id="arrival_date">
        </div>
        <div>
            <label for="departure_date">Departure Date</label>
            <input type="text" name="departure_date" id="departure_date">
        </div>
        <br />
        <button>Search Flights</button>
    </form>

    <br /><br />
    
    <button type="button" class="btn1" onclick="location.href='index.php'">Home</button>
    <table id="demo1"></table>
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