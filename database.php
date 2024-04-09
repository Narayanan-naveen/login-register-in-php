<?php

$db_server = "localhost";
$db_user ="root";
$db_pass ="";
$db_name ="project1db";

try{
    $conn = mysqli_connect($db_server,$db_user,$db_pass,$db_name);
//  echo"printing";  
}
catch(mysqli_sql_exception){
    echo "not connect!!!!!!";
}
?>