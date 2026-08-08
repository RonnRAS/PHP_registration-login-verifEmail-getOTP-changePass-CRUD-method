<?php

session_start();
include "validateInputsFromUsers.php";
sendBack('User');


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
</head>

<body>
    <h1>User Dashboard</h1>

    <a onclick="window.location.href = 'logout.php' ">logout</a>

</body>

</html>