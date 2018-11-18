<?php 
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'printer_materials');
    $con = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    mysqli_select_db($con,"printer_materials");
    $row = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM materials LIMIT 1"));
    mysqli_close($con);
    echo $row[0]," ",$row[1]," ",$row[2]," ",$row[3]; ?>