<?php

session_start();

$cartArray = json_decode($_POST["cartArray"]);

$mysql = require __DIR__ . "/db.php";

$phone = $_SESSION["phonenumber"];

$status = "Booked";

$boolean = "not booked";

$missed = "not missed";

foreach($cartArray as $arr){

    $sqlQuery = sprintf("Select * from booking where flightID = %d",$arr);

    $oldbooking = $mysql->query($sqlQuery);

    if($oldbooking->num_rows < 1){

        $insertQuery = "Insert into booking (flightID, PassengerID, Status) values (?,?,?)";

        $stmt = $mysql->stmt_init();

        if (!$stmt->prepare($insertQuery)) {
            die("SQL error: " . $mysql->error);
        }

        $stmt->bind_param("sss",$arr,$phone,$status);

        if ($stmt->execute()) {
            $boolean = "booked";
        } else {
            $boolean = "not booked";
        }
    }
    else{
        $missed = "missed";
    }
}

if($missed == "missed"){
    echo "missed";
}
else{
    if($boolean == "booked"){
        echo "booked";
    }
    else{
        echo "not booked";
    }
}
