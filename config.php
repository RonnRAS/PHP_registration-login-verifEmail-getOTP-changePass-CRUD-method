<?php 
$db_sever = "localhost";
$db_root = "root";
$db_pass = "";
$db_name = "personalprojecttest";


try {
    $conn = mysqli_connect($db_sever, $db_root, $db_pass, $db_name );
} catch (mysqli_sql_exception) {
    
}

?>