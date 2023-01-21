<?php

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $mysqli = require __DIR__ . "/db.php";

    $sql = sprintf("SELECT * FROM users
                    WHERE PassengerID = '%s'",
                   $mysqli->real_escape_string($_POST["phonenumber"]));

    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();

    if ($user) {

        if (password_verify($_POST["password"], $user["Password"])) {

            session_start();

            session_regenerate_id();

            $_SESSION["phonenumber"] = $user["PassengerID"];

            header("Location: index.php");

            exit;
        }
    }

    $is_invalid = true;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css" />
</head>
<body>

    <h1>Login</h1>

    <?php if ($is_invalid): ?>
    <em>Invalid login</em>
    <?php endif; ?>

    <form method="post">
        <label for="phonenumber">PassengerID/Phone Number</label>
        <input type="text" name="phonenumber" id="phonenumber"
            value="<?= $_POST["phonenumber"] ?? "" ?>" />

        <label for="password">Password</label>
        <input type="password" name="password" id="password" />

        <button>Log in</button>
        <button type="button" class="btn1" onclick="location.href='registration.html'">Register</button>
    </form>

</body>
</html>