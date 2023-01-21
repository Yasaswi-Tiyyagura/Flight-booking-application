<?php

if(empty($_POST["phonenumber"])) {
    die("Phone Number is required");
}

if (strlen($_POST["phonenumber"]) != 10) {
    die("Phone Number must be 10 characters");
}

if (!preg_match("/[0-9]/", $_POST["phonenumber"])) {
    die("Phone Number must contain numbers");
}

$phone = $_POST["phonenumber"];

if (empty($_POST["firstname"])) {
    die("First Name is required");
}

$firstname = $_POST["firstname"];

if (empty($_POST["lastname"])) {
    die("Last Name is required");
}

$lastname = $_POST["lastname"];

if (empty($_POST["age"])) {
    die("Age is required");
}

if (!preg_match("/[0-9]/", $_POST["age"])) {
    die("Age must contain numbers");
}

$age = intval($_POST["age"]);

$email_regex = "/([a-zA-Z0-9!#$%&’?^_`~-])+@([a-zA-Z0-9-])+(.edu)+/";

if (empty($_POST["email"])) {
    die("Email is required");
}

if (!preg_match($email_regex,$_POST["email"])) {
    die("Email is invalid");
}

$email = $_POST["email"];

if (strlen($_POST["password"]) < 8) {
    die("Password must be at least 8 characters");
}

if (!preg_match("/[a-z]/i", $_POST["password"])) {
    die("Password must contain at least one letter");
}

if (!preg_match("/[0-9]/", $_POST["password"])) {
    die("Password must contain at least one number");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Passwords must match");
}

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$mysqli = require __DIR__ . "/db.php";

$sql = "INSERT INTO users (PassengerID, FirstName, LastName, Age, Email, Password)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $mysqli->stmt_init();

if (!$stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("sssiss",$phone,$firstname,$lastname,$age,$email,$password_hash);

if ($stmt->execute()) {

    header("Location: register-success.html");
    exit;

} else {

    if ($mysqli->errno === 1062) {
        die("Phone Number already taken");
    } else {
        die($mysqli->error . " " . $mysqli->errno);
    }
}